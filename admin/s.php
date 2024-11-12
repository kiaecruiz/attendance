<?php
include('db_connection.php');
// Query to get attendance data for all months and grades
$sql = "
    SELECT 
        s.grade_id,
        DATE_FORMAT(a.time_in, '%Y-%m') AS month,
        COUNT(a.attendance_id) AS total_attendance,
        SUM(CASE WHEN a.remarks = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM 
        attendance a
    JOIN 
        students s ON a.student_id = s.student_id
    GROUP BY 
        s.grade_id, DATE_FORMAT(a.time_in, '%Y-%m')
    ORDER BY 
        s.grade_id, month
";

$result = $conn->query($sql);

$attendance_data = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $grade_id = $row['grade_id'];
        $month = $row['month'];
        $total = $row['total_attendance'];
        $present = $row['present_count'];
        $attendance_percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

        // Store the data in an associative array
        $attendance_data[] = [
            'grade_id' => $grade_id,
            'month' => $month,
            'attendance_percentage' => $attendance_percentage
        ];
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Percentage by Grade (All Months)</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General styling for the body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main container styling */
        .container {
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        /* Header Styling */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* Graph container styling */
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .container2 {
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Student Attendance Percentage by Grade (All Months)</h2>

    <!-- Chart.js container -->
    <div class="chart-container">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

<script>
    // PHP data passed to JavaScript
    var attendanceData = <?php echo json_encode($attendance_data); ?>;

    // Get unique grades and months from the data
    var grades = [...new Set(attendanceData.map(item => item.grade_id))];
    var months = [...new Set(attendanceData.map(item => item.month))];

    // Prepare the chart data
    var chartData = {
        labels: months,  // X-axis: Months
        datasets: []
    };

    // Add a dataset for each grade
    grades.forEach(function(grade) {
        var gradeData = months.map(function(month) {
            // Filter the data based on the current grade and month
            var monthData = attendanceData.filter(function(item) {
                return item.month === month && item.grade_id === grade;
            });

            // If no data for this grade and month, return 0
            return monthData.length > 0 ? monthData[0].attendance_percentage : 0;
        });

        // Push the dataset for this grade
        chartData.datasets.push({
            label: 'Grade ' + grade,
            data: gradeData,  // Y-axis: Attendance Percentage for this grade
            backgroundColor: 'rgba(' + (Math.random() * 255) + ',' + (Math.random() * 255) + ',' + (Math.random() * 255) + ', 0.6)',
            borderColor: 'rgba(0, 0, 0, 1)',
            borderWidth: 1
        });
    });

    // Get the canvas element to draw the chart
    var ctx = document.getElementById('attendanceChart').getContext('2d');

    // Create the chart
    var attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value + "%" }  // Show percentage sign on Y-axis
                    }
                }
            }
        }
    });
</script>


<?php
$sql = "
SELECT 
    g.grade_id,
    g.grade,
    DATE_FORMAT(a.time_in, '%Y-%m') AS month,
    COUNT(DISTINCT s.student_id) AS total_students_in_grade,  -- Total students per grade
    ROUND(
        (SUM(CASE WHEN a.remarks = 'Present' THEN 1 ELSE 0 END) / COUNT(DISTINCT s.student_id)) * 100, 
        2
    ) AS avg_attendance_percentage
FROM 
    grade g
LEFT JOIN 
    students s ON g.grade_id = s.grade_id  -- Join to get all students in the grade
LEFT JOIN 
    attendance a ON s.student_id = a.student_id AND DATE_FORMAT(a.time_in, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')  -- Join attendance data
GROUP BY 
    g.grade_id, DATE_FORMAT(a.time_in, '%Y-%m')
ORDER BY 
    g.grade_id, month;
";

$result = $conn->query($sql);

$attendance_data = [];

if ($result->num_rows > 0) {
// Collect the data for the table
while($row = $result->fetch_assoc()) {
    $attendance_data[] = $row;
}
}


?>
<br>
<div class="container2">
    <h2>Monthly Average Attendance Per Section</h2>

    <table>
        <thead>
            <tr>
                <th>Grade</th>
                <th>Month</th>
                <th>Total Students</th>
                <th>Average Attendance Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($attendance_data as $data): ?>
                <tr>
                    <td><?php echo $data['grade']; ?></td>
                    <td><?php echo $data['month']; ?></td>
                    <td><?php echo $data['total_students_in_grade']; ?></td>
                    <td><?php echo $data['avg_attendance_percentage'] . '%'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
