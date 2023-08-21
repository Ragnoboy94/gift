<?php

namespace App\Http\Controllers;

use App\Models\OrderProblem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderProblemListController extends Controller
{
    public function index()
    {
        $unresolvedProblems = OrderProblem::with('order', 'comments')
            ->where('resolved', false)
            ->orderByDesc('created_at') // Сначала самые свежие по дате
            ->get();

        $resolvedProblems = OrderProblem::with('order', 'comments')
            ->where('resolved', true)
            ->orderByDesc('created_at') // Сначала самые свежие по дате
            ->get();

        $problemData = collect([...$unresolvedProblems, ...$resolvedProblems])->map(function ($problem) {
            $timeAndVariation = $this->getTimeAndVariation($problem->order->created_at, $problem->created_at);

            $comment = null;
            $resolvedBy = null;
            $resolvedAt = null;

            if ($problem->resolved) {
                $comment = $problem->comments;
                $resolvedBy = $comment->resolved_by;
                $resolvedAt = $comment->created_at->format('d-m-Y H:i:s');

            }

            return [
                'order' => $problem->order,
                'description' => $problem->description,
                'sum' => $problem->order->sum,
                'orderCreatedAt' => $problem->order->created_at->format('d-m-Y H:i:s'),
                'problemCreatedAt' => $problem->created_at->format('d-m-Y H:i:s'),
                'time' => $timeAndVariation['0'],
                'variation' => $timeAndVariation['1'],
                'comments' => $comment ? $comment->comment : null,
                'resolved' => $problem->resolved,
                'resolvedBy' => $resolvedBy,
                'resolvedAt' => $resolvedAt,
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



    public function resolve(OrderProblem $problem, Request $request)
    {
        // Помечаем проблему как решенную
        $problem->resolved = true;
        $problem->save();

        // Сохраняем комментарий
        $comment = $request->input('comment');
        if ($comment) {
            $problem->comments()->create([
                'comment' => $comment,
                'resolved_by' => auth()->user()->id,
            ]);
        }

        return redirect()->back()->with('message', 'Проблема помечена как решенная.');
    }

}
