<!-- app/views/admin/calendar.php -->

<h1 class="display-6 fw-bold mb-4">Reservations Calendar</h1>

<div class="alert alert-info" role="alert">
  <i class="bi bi-info-circle-fill"></i>
  <strong>Note:</strong> This is a static calendar view. For an interactive calendar, you will need to integrate a JavaScript library like <strong>FullCalendar.js</strong>.
</div>

<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header text-center fs-4 fw-light">
        <!-- Static calendar navigation controls -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="#" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Previous Month</a>
            <span class="fw-bold">October 2025</span>
            <a href="#" class="btn btn-outline-primary">Next Month <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Calendar Placeholder (Bootstrap Table) -->
        <div class="table-responsive">
            <table class="table table-bordered text-center calendar-table" style="table-layout: fixed;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 14.28%;">Monday</th>
                        <th style="width: 14.28%;">Tuesday</th>
                        <th style="width: 14.28%;">Wednesday</th>
                        <th style="width: 14.28%;">Thursday</th>
                        <th style="width: 14.28%;">Friday</th>
                        <th style="width: 14.28%;">Saturday</th>
                        <th style="width: 14.28%;">Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Week 1 -->
                    <tr>
                        <td class="text-muted">29</td>
                        <td class="text-muted">30</td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                    </tr>
                    <!-- Week 2 -->
                    <tr>
                        <td>6</td>
                        <td>7</td>
                        <td>8<span class="badge bg-success d-block mt-1">2</span></td>
                        <td>9</td>
                        <td>10<span class="badge bg-success d-block mt-1">1</span></td>
                        <td>11</td>
                        <td>12</td>
                    </tr>
                    <!-- Week 3 -->
                    <tr>
                        <td>13</td>
                        <td>14</td>
                        <td>15</td>
                        <td>16<span class="badge bg-success d-block mt-1">3</span></td>
                        <td>17</td>
                        <td>18</td>
                        <td>19</td>
                    </tr>
                    <!-- Week 4 -->
                    <tr>
                        <td>20</td>
                        <td>21</td>
                        <td>22</td>
                        <td>23</td>
                        <td>24</td>
                        <td>25</td>
                        <td>26</td>
                    </tr>
                    <!-- Week 5 -->
                    <tr>
                        <td>27<span class="badge bg-secondary d-block mt-1">1</span></td>
                        <td>28<span class="badge bg-warning text-dark d-block mt-1">1</span></td>
                        <td>29</td>
                        <td>30</td>
                        <td>31</td>
                        <td class="text-muted">1</td>
                        <td class="text-muted">2</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Legend -->
        <div class="p-3 border-top">
            <span class="badge bg-warning text-dark me-2">Pending</span>
            <span class="badge bg-success me-2">Confirmed</span>
            <span class="badge bg-secondary me-2">Completed</span>
        </div>
    </div>
</div>

<!-- Simple CSS so the calendar isn't too tall -->
<style>
    .calendar-table td {
        height: 100px;
        vertical-align: top;
        padding-top: 8px;
    }
</style>
