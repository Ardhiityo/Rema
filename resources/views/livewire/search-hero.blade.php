  <div class="py-5 container-fluid feature bg-light" id="repositories">
      <div class="container py-5">
          <div class="py-4 mx-auto text-center wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
              <h4 class="text-primary">Solusi Akademik Digital</h4>
              <h1 class="display-5">Temukan inspirasi akademikmu.</h1>
              <p class="mb-0">Temukan inspirasi akademik dari ribuan karya mahasiswa. Dengan sistem pencarian cerdas
                  dan tampilan yang ramah pengguna, Rema hadir untuk mendukung perjalanan belajarmu.
              </p>
          </div>
          <div class="pb-3 row" id="search-hero">
              <div class="col-12">
                  {{-- Display Large Only --}}
                  <div class="mb-3 d-lg-flex d-none input-group input-group-lg">
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select" id="inputGroupSelect01" wire:model.live='type'>
                          <option selected value="">Type</option>
                          <option value="thesis">Skripsi</option>
                          <option value="final_project">Tugas Akhir</option>
                          <option value="manual_book">Manual Book</option>
                      </select>
                      <button class="btn btn-primary d-flex align-items-center" wire:click='resetInput'
                          wire:loading.attr='disabled' wire:target='resetInput'>
                          <i class="bi bi-arrow-clockwise" wire:loading.class='d-none' wire:target='resetInput'></i>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
                  {{-- Display Medium Only --}}
                  <div class="mb-3 d-md-flex d-none d-lg-none input-group">
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select" id="inputGroupSelect01" wire:model.live='type'>
                          <option selected value="">Type</option>
                          <option value="thesis">Skripsi</option>
                          <option value="final_project">Tugas Akhir</option>
                          <option value="manual_book">Manual Book</option>
                      </select>
                      <button class="btn btn-primary" wire:click='resetInput' wire:target='resetInput'>
                          <i class="bi bi-arrow-clockwise" wire:loading.class='d-none' wire:target='resetInput'></i>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
                  {{-- Display Small Only --}}
                  <div class="flex-wrap gap-2 mb-3 d-md-none d-flex d-lg-none">
                      <input type="text" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Title" wire:model.live.debounce.250ms='title'>
                      <input type="text" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Author" wire:model.live.debounce.250ms='author'>
                      <input type="number" class="form-control w-100" aria-label="Text input with dropdown button"
                          placeholder="Year" wire:model.live.debounce.250ms='year'>
                      <select class="form-select w-100" id="inputGroupSelect01" wire:model.live='type'
                          wire:target='resetInput'>
                          <option selected value="">Type</option>
                          <option value="thesis">Skripsi</option>
                          <option value="final_project">Tugas Akhir</option>
                          <option value="manual_book">Manual Book</option>
                      </select>
                      <button class="btn btn-primary w-100" wire:click='resetInput' wire:target='resetInput'>
                          <span wire:loading.class='d-none' wire:target='resetInput'>
                              <i class="bi bi-arrow-clockwise"></i> <small>Reset</small>
                          </span>
                          <span wire:loading wire:target='resetInput'>
                              <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                          </span>
                      </button>
                  </div>
              </div>
          </div>
          <div class="gap-4 row d-flex gap-md-0">
              @forelse ($repositories as $repository)
                  <div class="col-md-6 col-lg-6 col-xl-3 d-flex">
                      <a href="{{ route('repository.read', ['repository' => $repository->slug]) }}" target="_blank"
                          class="w-100">
                          <div class="p-4 pt-0 border feature-item h-100 d-flex flex-column justify-content-between">
                              <div>
                                  <div class="p-4 mb-4 feature-icon">
                                      <i class="{{ $repository->icon_class }}"></i>
                                  </div>
                                  <div class="mb-4">
                                      <h4>{{ $repository->short_author_name }}</h4>
                                      <p><small>{{ $repository->nim }} | {{ $repository->study_program }}</small></p>
                                  </div>
                                  <p class="mb-4">{{ $repository->short_title }}</p>
                              </div>
                              <div>
                                  <p><small><i class="far fa-clock"></i>
                                          {{ $repository->published_at_to_diff_for_humans }}</small></p>
                                  <span class="px-4 py-2 btn btn-primary rounded-pill">
                                      View <i class="fas fa-eye ms-2"></i>
                                  </span>
                              </div>
                          </div>
                      </a>
                  </div>
              @empty
                  <div class="col-12 d-flex justify-content-center">
                      <div class="mt-4">
                          <h5 class="text-muted">
                              Ups, no repositories available
                              <i class="far fa-frown-open"></i>
                          </h5>
                      </div>
                  </div>
              @endforelse
              <div class="py-3 col-12 d-flex justify-content-end">
                  {{ $repositories->Links() }}
              </div>
          </div>
      </div>
  </div>
