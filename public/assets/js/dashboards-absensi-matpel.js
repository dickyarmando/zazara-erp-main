/**
 * Dashboard eCommerce
 */

'use strict';

(function () {
  let labelColor, shadeColor, borderColor;

  // Total Income - Area Chart
  // --------------------------------------------------------------------
  const totalIncomeEl = document.querySelector('#totalIncomeChart'),
    totalIncomeConfig = {
      chart: {
        height: 250,
        type: 'area',
        toolbar: false,
        dropShadow: {
          enabled: true,
          top: 14,
          left: 2,
          blur: 3,
          color: config.colors.primary,
          opacity: 0.15
        }
      },
      series: [
        {
          data: [4, 4, 1, 4]
        }
      ],
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 3,
        curve: 'straight'
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: shadeColor,
          shadeIntensity: 0.8,
          opacityFrom: 0.7,
          opacityTo: 0.25,
          stops: [0, 95, 100]
        }
      },
      grid: {
        show: true,
        borderColor: borderColor,
        padding: {
          top: -15,
          bottom: -10,
          left: 0,
          right: 0
        }
      },
      xaxis: {
        categories: ['MTK', 'Bhs. Indonesia', 'Bhs. Inggris', 'IPAS'],
        labels: {
          offsetX: 0,
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        lines: {
          show: false
        }
      },
      yaxis: {
        labels: {
          offsetX: -15,
          formatter: function (val) {
            if(val == 4)
            {
              return 'H';
            }else if(val == 2){
              return 'I';
            }else if(val == 3){
              return 'S';
            }else{
              return 'A';
            }
          },
          style: {
            fontSize: '13px',
            colors: labelColor
          }
        }
      }
    };
  if (typeof totalIncomeEl !== undefined && totalIncomeEl !== null) {
    const totalIncome = new ApexCharts(totalIncomeEl, totalIncomeConfig);
    totalIncome.render();
  }

})();
