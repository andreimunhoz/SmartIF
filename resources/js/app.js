// =================================================================
// 1. Lógica do Dark Mode (Executa primeiro)
// =================================================================
// Script para aplicar o tema salvo ANTES da página carregar
if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.classList.remove('dark')
}

// =================================================================
// 2. Importações
// =================================================================
import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts; // Disponibiliza globalmente, como o CDN fazia

import './bootstrap'; // Bootstrap do Laravel (já estava aí)


// =================================================================
// 3. Lógica Principal da Aplicação (Menus, Gráficos)
// =================================================================
document.addEventListener("DOMContentLoaded", () => {
  // Scripts dos menus dropdown
  const userMenuButton = document.getElementById('user-menu-button');
  const userMenu = document.getElementById('user-menu');
  const notificationsButton = document.getElementById('notifications-button');
  const notificationsMenu = document.getElementById('notifications-menu');
  let donutChart, areaChart;

  // Verificamos se os botões existem antes de adicionar listeners
  if (userMenuButton) {
    userMenuButton.addEventListener('click', () => userMenu.classList.toggle('hidden'));
  }
  
  if (notificationsButton) {
    notificationsButton.addEventListener('click', () => notificationsMenu.classList.toggle('hidden'));
  }

  // Fechar menus ao clicar fora
  window.addEventListener('click', (e) => {
    if (userMenuButton && !userMenuButton.contains(e.target) && userMenu && !userMenu.contains(e.target)) {
        userMenu.classList.add('hidden');
    }
    if (notificationsButton && !notificationsButton.contains(e.target) && notificationsMenu && !notificationsMenu.contains(e.target)) {
        notificationsMenu.classList.add('hidden');
    }
  });

  // Lógica do Toggle de Tema
  const themeToggleBtn = document.getElementById('theme-toggle');
  
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {
      // Alterna a classe no <html>
      document.documentElement.classList.toggle('dark');
      
      const isDarkMode = document.documentElement.classList.contains('dark');
      
      // Salva a preferência no localStorage
      localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
      
      // Atualiza os gráficos
      updateChartThemes(isDarkMode);
    });
  }

  // Função para atualizar os gráficos
  function updateChartThemes(isDarkMode) {
    const mode = isDarkMode ? 'dark' : 'light';
    const donutTextColor = isDarkMode ? '#F3F4F6' : '#1F2937';
    const areaTextColor = isDarkMode ? '#9CA3AF' : '#6B7280';
    const gridBorderColor = isDarkMode ? '#374151' : '#E5E7EB';

    if (donutChart) {
      donutChart.updateOptions({
        theme: { mode: mode },
        plotOptions: {
          pie: {
            donut: {
              labels: { total: { color: donutTextColor } }
            }
          }
        }
      });
    }
    
    if (areaChart) {
      areaChart.updateOptions({
        theme: { mode: mode },
        xaxis: {
            labels: { style: { colors: areaTextColor } }
        },
        yaxis: {
          title: { style: { color: areaTextColor } },
          labels: { style: { colors: areaTextColor } }
        },
        legend: {
          labels: { colors: donutTextColor }
        },
        grid: {
          borderColor: gridBorderColor
        },
        tooltip: { theme: mode }
      });
    }
  }

  // Scripts dos Gráficos
  const renderCharts = () => {
    // === MELHORIA: Só renderiza os gráficos se as divs existirem ===
    const donutElement = document.querySelector("#chart-chamados");
    const areaElement = document.querySelector("#weekly-activity-chart");

    // Se não estamos no dashboard (ex: pág de perfil), nem tenta.
    if (!donutElement || !areaElement) {
        return;
    }
    // ==============================================================

    const isDarkMode = document.documentElement.classList.contains('dark');
    const mode = isDarkMode ? 'dark' : 'light';
    const donutTextColor = isDarkMode ? '#F3F4F6' : '#1F2937';
    const areaTextColor = isDarkMode ? '#9CA3AF' : '#6B7280';
    const gridBorderColor = isDarkMode ? '#374151' : '#E5E7EB';

    const donutOptions = {
      series: [12, 5, 87],
      chart: { type: 'donut', height: '100%', background: 'transparent' },
      theme: { mode: mode },
      labels: ['Em Aberto', 'Em Andamento', 'Fechados'],
      colors: ['#FBBF24', '#3B82F6', '#008B4A'],  
      dataLabels: { enabled: false },
      legend: { show: false },
      tooltip: { theme: mode, y: { formatter: (val) => val + " chamados" } },
      stroke: { width: 0 },
      plotOptions: {
        pie: {
          donut: {
            size: '75%',
            labels: {
              show: true,
              total: {
                show: true,
                showAlways: true,
                label: 'Total',
                fontSize: '16px',
                fontWeight: '600',
                color: donutTextColor,
                formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
              }
            }
          }
        }
      }
    };
    donutChart = new ApexCharts(donutElement, donutOptions);
    donutChart.render();

    const areaOptions = {
        series: [{ name: 'Abertos', data: [4, 5, 8, 6, 7, 5, 9] }, { name: 'Fechados', data: [3, 6, 7, 8, 6, 7, 10] }],
        chart: { type: 'area', height: 250, background: 'transparent', toolbar: { show: false }, zoom: { enabled: false } },
        theme: { mode: mode },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            labels: { style: { colors: areaTextColor } },
            axisBorder: { show: false },
        },
        yaxis: { title: { text: 'Chamados', style: { color: areaTextColor } }, labels: { style: { colors: areaTextColor } } },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.9, stops: [0, 90, 100] } },
        legend: { position: 'top', horizontalAlign: 'left', labels: { colors: donutTextColor } },
        colors: ['#FBBF24', '#008B4A'],  
        grid: { borderColor: gridBorderColor, strokeDashArray: 4 },
        tooltip: { theme: mode }
    };
    areaChart = new ApexCharts(areaElement, areaOptions);
    areaChart.render();
  };
  
  renderCharts();
});