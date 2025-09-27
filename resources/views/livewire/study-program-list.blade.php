  <div class="card">
      <div class="card-header">
          <div class="gap-3 d-flex align-items-center">
              <input type="text" wire:model.live.debounce.250ms='keyword' class="form-control" id="basicInput"
                  placeholder="Search...">
              <button class="btn btn-primary" wire:click='resetInput'>
                  <i class="bi bi-arrow-clockwise"></i>
              </button>
          </div>
      </div>
      <div class="card-content">
          <div class="table-responsive">
              <table class="table mb-0 text-center table-lg">
                  <thead>
                      <tr class="text-nowrap">
                          <th>No</th>
                          <th>Study Program</th>
                          <th>Created At</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($study_programs as $study_program)
                          <tr class="text-nowrap">
                              <td class="text-bold-500">{{ $loop->index + $study_programs->firstItem() }}</td>
                              <td class="text-bold-500">{{ $study_program->name }}</td>
                              <td>13 Januari 2025</td>
                              <td class="gap-3 d-flex justify-content-center align-items-center">
                                  <a href="" class="btn btn-warning">
                                      <i class="bi bi-pencil-square"></i>
                                  </a>
                                  <form action="" method="post">
                                      <button class="btn btn-danger">
                                          <i class="bi bi-trash3"></i>
                                      </button>
                                  </form>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="4" class="text-center">Data Not Found</td>
                          </tr>
                      @endforelse
                      <tr>
                  </tbody>
              </table>
          </div>
          @if ($study_programs->isNotEmpty())
              <div class="p-3 pt-4">
                  {{ $study_programs->links() }}
              </div>
          @endif
      </div>
  </div>
