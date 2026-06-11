const ctx = document.getElementById("employeePerformance").getContext("2d");
const ctx2 = document.getElementById("clientStats").getContext("2d");
let delayed; // Variable para controlar la animación

const config = {
    type: "bar", // Tipo de gráfica de barras
    data: {
        // Nombres de tus empleados o esteticistas
        labels: ["Ana Silva", "Carlos Mendoza", "María Gómez", "Elena Rondón"],
        datasets: [
            {
                label: "Servicios Realizados",
                data: [24, 18, 35, 12], // Cantidad de citas completadas por cada uno

                // Jugamos con transparencias RGBA para que combine con el Glassmorphism
                backgroundColor: [
                    "rgba(54, 162, 235, 0.5)", // Azul traslúcido
                    "rgba(255, 99, 132, 0.5)", // Rosado traslúcido
                    "rgba(75, 192, 192, 0.5)", // Verde menta traslúcido
                    "rgba(255, 206, 86, 0.5)", // Amarillo traslúcido
                ],
                // Bordes un poco más sólidos para dar definición de cristal
                borderColor: [
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 99, 132, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(255, 206, 86, 1)",
                ],
                borderWidth: 1.5,
                borderRadius: 8, // Suaviza las esquinas de las barras para un look moderno
            },
        ],
    },
    options: {
        indexAxis: "y", // 🚀 ESTO HACE QUE LAS BARRAS SEAN HORIZONTALES (Perfecto para nombres)
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false, // Ocultamos la leyenda superior porque ya es redundante
            },
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    color: "rgba(0, 0, 0, 0.05)", // Líneas de guía muy sutiles
                },
                ticks: {
                    stepSize: 5, // Escala de 5 en 5 citas
                },
            },
            y: {
                grid: {
                    display: false, // Quitamos las líneas verticales de fondo para limpieza visual
                },
            },
        },
    },
};

const config2 = {
    type: "bar", // Tipo de gráfica de barras
    data: {
        // Nombres de tus empleados o esteticistas
        labels: ["Enero", "Febrero", "Marzo", "Abril"],
        datasets: [
            {
                label: "Clientes atendidos",
                data: [2, 3, 1, 5], // Cantidad de citas completadas por cada uno

                // Jugamos con transparencias RGBA para que combine con el Glassmorphism
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 206, 86, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        },
};

new Chart(ctx, config);
new Chart(ctx2, config2);
