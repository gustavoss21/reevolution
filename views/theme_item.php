<?php
// include layout file
$layout = __DIR__ . '/layout/index.php';
$title = "ANALISE";
?>

<main class="container">
    <h1>Analize de Evento</h1>
    <div class="block-container block-stage">
        <h1>📅 Painel de Análise de Estudos</h1>

        <div class="charts">
            <!-- 📊 Gráfico de quantidade por status -->
            <div class="chart-container">
                <h3>Matérias por Status</h3>
                <canvas id="chartStatusQtd"></canvas>
            </div>

            <!-- 📈 Gráfico de média de pontuação por status -->
            <div class="chart-container">
                <h3>Média de Pontuação por Status</h3>
                <canvas id="chartStatusMedia"></canvas>
            </div>
        </div>

        <!-- 🥇 Top 5 matérias -->
        <h2 style="margin-top:40px;">Top 5 Matérias Mais Urgentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Prioridade</th>
                    <th>Domínio</th>
                    <th>Status</th>
                    <th>Última Atualização</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>'name'</td>
                    <td>priority</td>
                    <td>domain_level</td>
                    <td>status</td>
                    <td>updated_at</td>
                    <td><strong>pontuacao</strong></td>
                </tr>
            </tbody>
        </table>

        <script>
            const labels = <?= json_encode($statusLabels) ?>;

            // 📊 Gráfico de quantidade
            new Chart(document.getElementById('chartStatusQtd'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantidade de Matérias',
                        data: <?= json_encode($statusQtd) ?>,
                        backgroundColor: ['#f87171', '#60a5fa', '#34d399']
                    }]
                }
            });

            // 📈 Gráfico de média de pontuação
            new Chart(document.getElementById('chartStatusMedia'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Média de Pontuação',
                        data: <?= json_encode($statusMedia) ?>,
                        backgroundColor: ['#fbbf24', '#818cf8', '#10b981']
                    }]
                }
            });
        </script>

        </body>

        </html>
    </div>
</main>