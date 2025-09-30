  <div class="py-5 container-fluid feature bg-light" id="search-hero">
      <div class="container py-5">
          <div class="py-4 mx-auto text-center wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
              <h4 class="text-primary">Solusi Akademik Digital</h4>
              <h1 class="display-5">Temukan inspirasi akademikmu.</h1>
              <p class="mb-0">Temukan inspirasi akademik dari ribuan karya mahasiswa. Dengan sistem pencarian cerdas
                  dan tampilan yang ramah pengguna, Rema hadir untuk mendukung perjalanan belajarmu.
              </p>
          </div>
          <div class="pb-3 row">
              <div class="col-12">
                  <div class="col-12">
                      {{-- Display Large Only --}}
                      <div class="mb-3 d-lg-flex d-none input-group input-group-lg">
                          <input type="text" class="w-75 form-control" aria-label="Text input with dropdown button"
                              placeholder="Cari judul disini..." wire:model.live.debounce.250ms='title'>
                          <select class="w-auto form-select" id="inputGroupSelect01" wire:model.live='type'>
                              <option selected value="">Type</option>
                              <option value="thesis">Skripsi</option>
                              <option value="final_project">Tugas Akhir</option>
                              <option value="manual_book">Manual Book</option>
                          </select>
                          <button class="btn btn-primary" wire:click='resetInput'>
                              <i class="bi bi-arrow-clockwise"></i>
                          </button>
                      </div>
                      {{-- Display Small, Medium Only --}}
                      <div class="mb-3 d-flex d-lg-none input-group">
                          <input type="text" class="form-control w-50" aria-label="Text input with dropdown button"
                              placeholder="Judul" wire:model.live.debounce.250ms='title'>
                          <select class="form-select w-25" id="inputGroupSelect01" wire:model.live='type'>
                              <option selected value="">Type</option>
                              <option value="thesis">Skripsi</option>
                              <option value="final_project">Tugas Akhir</option>
                              <option value="manual_book">Manual Book</option>
                          </select>
                          <button class="btn btn-primary" wire:click='resetInput'>
                              <i class="bi bi-arrow-clockwise"></i>
                          </button>
                      </div>
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
              @endforelse

              <div class="py-3 col-12 d-flex justify-content-end">
                  {{ $repositories->Links() }}
              </div>
          </div>
      </div>
  </div>
