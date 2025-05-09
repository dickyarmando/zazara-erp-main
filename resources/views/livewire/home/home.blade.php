
<div>
    @section('title', 'Beranda')
    <x-flash-alert />

    <div class="row gy-4 mb-4">
    <div class="col-12 col-md-6">
        <div class="card p-3 h-100">
        <canvas id="monthly-sales-chart"></canvas>
        <div class="overflow-auto mt-3">
            <table class="table table-responsive">
            <thead>
                <tr>
                  <th class="text-start" width="70%">Bulan</th>
                  <th class="text-center" width="30%">Penjualan</th>
                </tr>
            </thead>
            <tbody id="monthly-sales-table">
            </tbody>
            </table>
        </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card p-3 h-100">
        <canvas id="yearly-sales-chart"></canvas>
        <div class="overflow-auto mt-3">
            <table class="table table-responsive">
            <thead>
                <tr>
                  <th class="text-start" width="70%">Tahun</th>
                  <th class="text-center" width="30%">Penjualan</th>
                </tr>
            </thead>
            <tbody id="yearly-sales-table">
            </tbody>
            </table>
        </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card p-3 overflow-auto" style="height: 38.5em">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th class="text-start fs-5 align-middle fw-bold" colspan="2">Top 10 Customer</th>
                <th class="text-center align-middle">
                  <select class="form-control" wire:model="top_customer_year">
                      @foreach ($allSalesYear as $sales)
                          <option value="{{ $sales->year }}">{{ $sales->year }}</option>
                      @endforeach
                  </select>
                </th>
            </tr>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="65%">Company</th>
                <th class="text-center" width="30%">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($topCustomer as $customer)
            <tr>
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td style="max-width: 18em" class="text-truncate">{{ $customer->customer }}</td>
                <td>
                <div class="d-flex justify-content-between">
                    <span>Rp</span>
                    <span class="font-tabular-nums" class="font-tabular-nums">{{ number_format($customer->total, 0) }}</span>
                </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card p-3 overflow-auto" style="height: 38.5em">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th class="text-start fs-5 align-middle fw-bold" colspan="2">List Piutang</th>
                <th class="text-center align-middle">
                  <select class="form-control" wire:model="credit_customer_year">
                      @foreach ($allSalesYear as $sales)
                          <option value="{{ $sales->year }}">{{ $sales->year }}</option>
                      @endforeach
                  </select>
                </th>
            </tr>
            <tr>
              <th class="text-center" width="5%">No</th>
              <th class="text-center" width="65%">Company</th>
              <th class="text-center" width="30%">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($creditCustomer as $customer)
            <tr>
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td style="max-width: 18em" class="text-truncate">{{ $customer->customer }}</td>
                <td>
                <div class="d-flex justify-content-between">
                    <span>Rp</span>
                    <span class="font-tabular-nums">{{ number_format($customer->rest, 0) }}</span>
                </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    </div>
  
    @push('scripts')
    <script src="{{ url('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
  
    <script>
      const monthlySalesChart = document.getElementById('monthly-sales-chart');
      const monthlySalesTable = document.getElementById('monthly-sales-table');
      const yearlySalesChart = document.getElementById('yearly-sales-chart');
      const yearlySalesTable = document.getElementById('yearly-sales-table');
  
      const monthlySales = @json($monthlySales);
      const monthlySalesMap = Object.fromEntries(
        monthlySales.map(s => [s.sales_month, parseFloat(s.total)])
      );
  
      const monthlyChartData = Array.from({ length: 12 }, (_, i) => {
        const month = i + 1;
        const total = monthlySalesMap[month] ?? 0
  
        monthlySalesTable.innerHTML += `
        <tr>
          <td class="text-start">${ new Intl.DateTimeFormat('id', { month: 'long' }).format(new Date(2000, i)) }</td>
          <td>
            <div class="d-flex justify-content-between">
              <span>Rp</span>
              <span class="font-tabular-nums">${ total.toLocaleString() }</span>
            </div>
          </td>
        </tr>
        `
        return {
          label: new Intl.DateTimeFormat('en', { month: 'short' }).format(new Date(2000, i)),
          total: total,
        };
      });
  
      new Chart(monthlySalesChart, {
        type: 'line',
        data: {
          labels: monthlyChartData.map(data => data.label),
          datasets: [
            {
              label: 'Penjualan',
              data: monthlyChartData.map(data => data.total),
              borderColor: "#156082",
              backgroundColor: "#156082",
            },
          ]
        },
        options: {
          plugins: {
            title: {
              display: true,
              text: 'Penjualan',
              font: {
                weight: 'bold',
                size: 18
              }
            },
            legend: {
              display: false,
            }
          }
        }
      })
  
      const yearlySales = @json($yearlySales);
      const yearlySalesMap = Object.fromEntries(
        yearlySales.map(s => [s.sales_year, parseFloat(s.total)])
      );
  
      const currentYear = new Date().getFullYear();
      const yearlyChartData = Array.from({ length: 5 }, (_, i) => {
        const year = currentYear - 4 + i;
        const total = yearlySalesMap[year] ?? 0
  
        yearlySalesTable.innerHTML += `
        <tr>
          <td class="text-start">${ year }</td>
          <td>
            <div class="d-flex justify-content-between">
              <span>Rp</span>
              <span class="font-tabular-nums">${ total.toLocaleString() }</span>
            </div>
          </td>
        </tr>
        `
        return {
          label: year,
          total: total,
        };
      });
  
      new Chart(yearlySalesChart, {
        type: 'bar',
        data: {
          labels: yearlyChartData.map(data => data.label),
          datasets: [
            {
              label: 'Penjualan Per Tahun',
              data: yearlyChartData.map(data => data.total),
              borderColor: "#E97132",
              backgroundColor: "#E97132",
            },
          ],
        },
        options: {
          plugins: {
            title: {
              display: true,
              text: 'Penjualan Per Tahun',
              font: {
                weight: 'bold',
                size: 18
              }
            },
            legend: {
              display: false,
            }
          }
        }
      })
    </script>
    @endpush
  </div>
  