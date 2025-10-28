<!-- app/views/admin/reservas.php -->

<h1 class="display-6 fw-bold mb-4">Manage Reservations</h1>

<!-- Table container card -->
<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="fw-light my-2">Reservation List</h3>
        <!-- Optional: Button to add a new reservation -->
        <a href="#" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Reservation
        </a>
    </div>
    <div class="card-body">
        <!-- Filters (optional) -->
        <form class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search by customer...">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All statuses...</option>
                    <option value="1">Pending</option>
                    <option value="2">Confirmed</option>
                    <option value="3">Canceled</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>

        <!-- Reservations Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Date</th>
                        <th scope="col">Origin</th>
                        <th scope="col">Destination</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- A long PHP loop with all reservations would go here -->
                    <tr>
                        <td>#1024</td>
                        <td>Ana García</td>
                        <td>28/10/2025 - 10:30</td>
                        <td>Airport</td>
                        <td>Hotel Palace</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="#" class="btn btn-sm btn-danger" title="Cancel"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>#1023</td>
                        <td>Carlos Pérez</td>
                        <td>28/10/2025 - 11:15</td>
                        <td>Hotel Sol</td>
                        <td>Airport</td>
                        <td><span class="badge bg-success">Confirmed</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                            <a href="#" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="#" class="btn btn-sm btn-danger" title="Cancel"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                     <tr>
                        <td>#1022</td>
                        <td>Laura Martínez</td>
                        <td>27/10/2025 - 19:00</td>
                        <td>Airport</td>
                        <td>Private Villa</td>
                        <td><span class="badge bg-secondary">Completed</span></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Bootstrap Pagination -->
        <nav aria-label="Reservation navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

