<x-action-section>
    <x-slot name="title">
        {{ __('session.delete_account') }}
    </x-slot>

    <x-slot name="description">
        {{ __('session.permanently_delete_account') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('session.account_deletion_warning') }}
        </div>

        <div class="mt-5">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                {{ __('session.delete_account_button') }}
            </button>
        </div>

        <!-- Confirm Deletion Modal -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">{{ __('session.confirm_deletion_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('session.confirm_deletion_body') }}
                    </div>
                    <div class="modal-footer">
                        <div class="lead text-center text-black" id="del-text"></div>
                        <button type="button" class="btn btn-secondary" id="close-button" data-bs-dismiss="modal">{{ __('session.cancel') }}</button>
                        <button type="button" id="confirm-delete-button" class="btn btn-danger">{{ __('session.confirm_deletion_button') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-action-section>
<script>
    let deleteButton = document.getElementById('confirm-delete-button');
    deleteButton.addEventListener('click', function() {
        deleteButton.remove();
        document.getElementById('close-button').remove();
        document.getElementById('del-text').innerText = "На вашу почту отправляется письмо...";
        fetch('/account/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(response => response.json())
            .then(data => {


                document.getElementById('del-text').innerText = data.message;
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
</script>

