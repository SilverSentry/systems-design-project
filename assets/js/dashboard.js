const weeklyCtxElement = document.getElementById("weeklyAppointmentsChart");
const monthlyCtxElement = document.getElementById("monthlyRevenueChart");
let delayed;

const weeklyConfig = {
    type: "line",
    data: {
        labels: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
        datasets: [
            {
                label: "Citas agendadas",
                data: [12, 15, 18, 20, 16, 14, 22],
                fill: true,
                backgroundColor: "rgba(54, 162, 235, 0.15)",
                borderColor: "rgba(54, 162, 235, 1)",
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: "rgba(54, 162, 235, 1)",
                pointBorderColor: "#fff",
                pointHoverRadius: 6,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.parsed.y} citas`; 
                    },
                },
            },
        },
        scales: {
            x: {
                grid: { display: false },
            },
            y: {
                beginAtZero: true,
                grid: { color: "rgba(0,0,0,0.05)" },
                ticks: { stepSize: 5 },
            },
        },
    },
};

const monthlyConfig = {
    type: "bar",
    data: {
        labels: ["Ene", "Feb", "Mar", "Abr", "May", "Jun"],
        datasets: [
            {
                label: "Ingresos",
                data: [4200, 5200, 6100, 5800, 6400, 7200],
                backgroundColor: "rgba(75, 192, 192, 0.5)",
                borderColor: "rgba(75, 192, 192, 1)",
                borderWidth: 1.5,
                borderRadius: 8,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return ` $${context.parsed.y.toLocaleString('es-ES')}`;
                    },
                },
            },
        },
        scales: {
            x: {
                grid: { display: false },
            },
            y: {
                beginAtZero: true,
                grid: { color: "rgba(0,0,0,0.05)" },
                ticks: {
                    callback: function(value) {
                        return `$${value / 1000}k`;
                    },
                },
            },
        },
    },
};

if (weeklyCtxElement) {
    const weeklyCtx = weeklyCtxElement.getContext("2d");
    new Chart(weeklyCtx, weeklyConfig);
}

if (monthlyCtxElement) {
    const monthlyCtx = monthlyCtxElement.getContext("2d");
    new Chart(monthlyCtx, monthlyConfig);
}
