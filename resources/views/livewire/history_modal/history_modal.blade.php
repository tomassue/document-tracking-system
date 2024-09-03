<!-- /* -------------------------------------------------------------------------- */
    /*                                historyModal                                */
    /* -------------------------------------------------------------------------- */ -->

<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="historyModalLabel">History</h1>
                <button type="button" class="btn-close" aria-label="Close" wire:click="$dispatch('hide-historyModal')"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <!-- <h6 class="card-title">Timeline</h6> -->
                    <div class="overflow-auto" id="content">
                        <ul class="timeline">
                            @foreach ($document_history as $item)
                            <li class="event" style="margin-bottom: 0px;" data-date="{{ $item->history_date_time }}">
                                <p class="fst-italic">{{ $item->history_date_time }}</p>
                                <h3 class="text-capitalize">{{ $item->status }}</h3>
                                <p>{{ $item->remarks . ' ' . $item->name }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('hide-historyModal')">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
    /*                              end historyModal                              */
    /* -------------------------------------------------------------------------- */ -->