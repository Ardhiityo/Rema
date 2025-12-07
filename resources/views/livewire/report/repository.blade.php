<div class="card">
    <div class="card-header">
        <h4 class="card-title">Repository</h4>
    </div>
    <div class="card-body">
        @if (session()->has('repository-failed'))
            <div class="alert-danger alert">
                {{ session('repository-failed') }}
            </div>
        @endif
        <div class="mb-4 row">
            <div class="mt-3 col-md-6 mt-md-0">
                <div class="gap-3 flex-column d-flex">
                    {{-- Year of Graduation --}}
                    <div>
                        <label for="year" class="form-label">
                            Year of Graduation
                            <sup>
                                *
                            </sup>
                        </label>
                        <input type="number" required class="form-control" id="year" wire:model='year'
                            placeholder="ex: 2025" name="year">
                        @error('year')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Year of Graduation --}}

                    {{-- Coordinator --}}
                    <div>
                        <label for="year" class="form-label">
                            Coordinator
                            <sup>
                                *
                            </sup>
                        </label>
                        <select class="form-select" id="coordinator_id" wire:model='coordinator_id'>
                            <option selected value="">Choose...</option>
                            @foreach ($coordinators as $coordinator)
                                <option value="{{ $coordinator->id }}">
                                    {{ $coordinator->nidn }} - {{ $coordinator->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('coordinator_id')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Coordinator --}}

                    {{-- Categories --}}
                    <div class="mt-2">
                        <label class="mb-2">Includes <sup>*</sup> </label>
                        <div class="gap-2 d-flex flex-column">
                            @foreach ($categories as $category)
                                <div class="form-check">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                            class="form-check-input form-check-primary form-check-glow" name="includes"
                                            wire:model='includes' value="{{ $category->slug }}"
                                            id="{{ $category->slug }}">
                                        <label class="form-check-label"
                                            for="{{ $category->slug }}">{{ $category->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('includes.*')
                            <span class="mt-3 badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Categories --}}
                </div>
            </div>
            <div class="gap-3 mt-4 d-flex">
                <button wire:click='download' wire:loading.attr='disabled' wire:target='download'
                    class="btn btn-primary">
                    <span wire:target='download' wire:loading.class='d-none'>Download</span>
                    <span wire:loading wire:target='download'>
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>

                <button wire:click='resetInput' class="btn btn-warning" wire:loading.attr='disabled'
                    wire:target='resetInput'>
                    <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                    <span wire:loading wire:target='resetInput'>
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
