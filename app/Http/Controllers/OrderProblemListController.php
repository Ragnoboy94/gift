<?php

namespace App\Http\Controllers;

use App\Models\OrderProblem;
use Carbon\Carbon;

class OrderProblemListController extends Controller
{
    public function index()
    {
        $unresolvedProblems = OrderProblem::with('order')->where('resolved', false)->get();

        $problemData = collect($unresolvedProblems)->map(function ($problem) {
            $timeAndVariation = $this->getTimeAndVariation($problem->order->created_at, $problem->created_at);

            return [
                'order' => $problem->order,
                'description' => $problem->description,
                'sum' => $problem->order->sum,
                'orderCreatedAt' => $problem->order->created_at,
                'problemCreatedAt' => $problem->created_at,
                'time' => $timeAndVariation['0'],
                'variation' => $timeAndVariation['1'],
            ];
        });

        return view('admin.problems.list', compact('problemData'));
    }


    // Метод для вычисления времени и вариации языкового отображения
    public function getTimeAndVariation($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $interval = $start->diff($end);
        $timeAndVariation = [];

        if ($interval->y > 0) {
            $years = $interval->y;
            $timeAndVariation[] = $years . ' ' . trans_choice('год|года|лет', $years);
            $end->subYears($years);
            $interval = $start->diff($end);
        }

        $months = $interval->m;

        if ($months > 0) {
            $timeAndVariation[] = $months . ' ' . trans_choice('месяц|месяца|месяцев', $months);
            $end->subMonths($months);
            $interval = $start->diff($end);
        }

        $weeks = floor($interval->days / 7);
        if ($weeks > 0) {
            $timeAndVariation[] = $weeks . ' ' . trans_choice('неделя|недели|недель', $weeks);
            $end->subWeeks($weeks);
            $interval = $start->diff($end);
        }

        $remainingDays = $interval->days % 7;

        if ($remainingDays > 0) {
            $timeAndVariation[] = $remainingDays . ' ' . trans_choice('день|дня|дней', $remainingDays);
            $end->subDays($remainingDays);
            $interval = $start->diff($end);
        }

        if ($interval->h > 0) {
            $timeAndVariation[] = $interval->h . ' ' . trans_choice('час|часа|часов', $interval->h);
        }

        if ($interval->i > 0) {
            $timeAndVariation[] = $interval->i . ' ' . trans_choice('минута|минуты|минут', $interval->i);
        }

        if ($interval->s > 0) {
            $timeAndVariation[] = $interval->s . ' ' . trans_choice('секунда|секунды|секунд', $interval->s);
        }

        return $timeAndVariation;
    }



    public function resolve(OrderProblem $problem)
    {
        // Помечаем проблему как решенную
        $problem->resolved = true;
        $problem->save();

        return redirect()->back()->with('message', 'Проблема помечена как решенная.');
    }
}
