<?php

/* ==========================================
   DECK CADET EMAIL TRACKING DASHBOARD
   ========================================== */

$csvFile = __DIR__ . '/opens.csv';

$records = [];

if (file_exists($csvFile)) {

    if (($handle = fopen($csvFile, 'r')) !== false) {

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) >= 5) {

                $records[] = [
                    'time' => $row[0] ?? '',
                    'id' => $row[1] ?? '',
                    'email' => $row[2] ?? '',
                    'ip' => $row[3] ?? '',
                    'user_agent' => $row[4] ?? ''
                ];
            }
        }

        fclose($handle);
    }
}


/* ==========================================
   STATISTICS
   ========================================== */

$totalOpens = count($records);

$uniqueRecipients = [];

foreach ($records as $record) {

    $email = strtolower(trim($record['email']));

    if ($email !== '') {
        $uniqueRecipients[$email] = true;
    }
}

$uniqueCount = count($uniqueRecipients);


/* ==========================================
   SORT — NEWEST FIRST
   ========================================== */

usort($records, function ($a, $b) {

    return strtotime($b['time']) <=> strtotime($a['time']);

});


/* ==========================================
   HELPER
   ========================================== */

function clean($value)
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Deck Cadet Email Tracker</title>

<style>

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    background: #f4f7fa;

    color: #263445;

    font-family:
        Inter,
        Arial,
        Helvetica,
        sans-serif;
}


/* =========================
   HEADER
========================= */

.header {

    background:
        linear-gradient(
            135deg,
            #0B1F3A,
            #123B63
        );

    color: white;

    padding: 28px 35px;

}

.header-inner {

    max-width: 1200px;

    margin: auto;

}

.logo {

    font-size: 13px;

    letter-spacing: 2px;

    color: #C9A84C;

    font-weight: 700;

}

.title {

    margin: 5px 0 0;

    font-size: 27px;

    font-weight: 800;

}

.subtitle {

    margin-top: 6px;

    color: #dbe5ef;

    font-size: 13px;

}


/* =========================
   CONTAINER
========================= */

.container {

    max-width: 1200px;

    margin: 30px auto;

    padding: 0 20px;

}


/* =========================
   STATS
========================= */

.stats {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 18px;

    margin-bottom: 25px;

}

.card {

    background: white;

    border:
        1px solid #e2e8f0;

    border-radius: 10px;

    padding: 22px;

    box-shadow:
        0 3px 12px
        rgba(11,31,58,.06);

}

.card-label {

    font-size: 11px;

    font-weight: 700;

    letter-spacing: 1px;

    color: #64748b;

    text-transform: uppercase;

}

.card-number {

    font-size: 30px;

    font-weight: 800;

    color: #0B1F3A;

    margin-top: 8px;

}


/* =========================
   TABLE
========================= */

.table-card {

    background: white;

    border:
        1px solid #e2e8f0;

    border-radius: 10px;

    overflow: hidden;

    box-shadow:
        0 3px 12px
        rgba(11,31,58,.06);

}

.table-header {

    padding: 20px;

    border-bottom:
        1px solid #e2e8f0;

    display: flex;

    justify-content:
        space-between;

    align-items: center;

}

.table-title {

    margin: 0;

    color: #0B1F3A;

    font-size: 16px;

    font-weight: 800;

}

.refresh {

    background: #0B1F3A;

    color: white;

    text-decoration: none;

    padding: 9px 15px;

    border-radius: 5px;

    font-size: 12px;

    font-weight: 700;

}

.refresh:hover {

    background: #123B63;

}


.table-wrapper {

    width: 100%;

    overflow-x: auto;

}

table {

    width: 100%;

    border-collapse:
        collapse;

    min-width: 850px;

}

th {

    background: #0B1F3A;

    color: white;

    text-align: left;

    padding: 12px 15px;

    font-size: 11px;

    letter-spacing: .7px;

    text-transform: uppercase;

}

td {

    padding: 13px 15px;

    border-bottom:
        1px solid #edf2f7;

    font-size: 12px;

    vertical-align: top;

}

tr:hover td {

    background: #f8fafc;

}


/* =========================
   STATUS
========================= */

.status {

    display: inline-block;

    padding: 5px 9px;

    border-radius: 20px;

    background: #eaf8ef;

    color: #16803c;

    font-size: 10px;

    font-weight: 800;

    letter-spacing: .5px;

}


/* =========================
   EMAIL
========================= */

.email {

    color: #123B63;

    font-weight: 700;

}

.tracking-id {

    font-family: monospace;

    color: #64748b;

    font-size: 11px;

}

.ip {

    font-family: monospace;

    font-size: 10px;

    color: #64748b;

}


/* =========================
   EMPTY
========================= */

.empty {

    padding: 50px;

    text-align: center;

    color: #64748b;

}

.empty strong {

    display: block;

    color: #0B1F3A;

    font-size: 16px;

    margin-bottom: 7px;

}


/* =========================
   FOOTER
========================= */

.footer {

    text-align: center;

    color: #94a3b8;

    font-size: 11px;

    padding: 25px;

}


/* =========================
   MOBILE
========================= */

@media (max-width: 700px) {

    .stats {

        grid-template-columns:
            1fr;

    }

    .header {

        padding: 22px;

    }

    .title {

        font-size: 22px;

    }

    .container {

        margin-top: 20px;

    }

}

</style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<header class="header">

    <div class="header-inner">

        <div class="logo">
            JATINDRA MEHER
        </div>

        <div class="title">
            Deck Cadet Email Tracker
        </div>

        <div class="subtitle">
            Email engagement monitoring dashboard
        </div>

    </div>

</header>


<!-- =========================
     MAIN
========================= -->

<main class="container">


<!-- STATISTICS -->

<div class="stats">


    <div class="card">

        <div class="card-label">
            Total Opens
        </div>

        <div class="card-number">
            <?= $totalOpens ?>
        </div>

    </div>


    <div class="card">

        <div class="card-label">
            Unique Recipients
        </div>

        <div class="card-number">
            <?= $uniqueCount ?>
        </div>

    </div>


    <div class="card">

        <div class="card-label">
            Last Open
        </div>

        <div class="card-number"
             style="font-size:18px;">

            <?php

            if (!empty($records)) {

                echo clean(
                    $records[0]['time']
                );

            } else {

                echo 'No data';

            }

            ?>

        </div>

    </div>

</div>


<!-- TABLE -->

<div class="table-card">


    <div class="table-header">

        <h2 class="table-title">
            Email Activity
        </h2>

        <a
            href=""
            class="refresh"
        >
            ↻ Refresh
        </a>

    </div>


    <?php if (empty($records)): ?>


        <div class="empty">

            <strong>
                No email opens recorded yet
            </strong>

            Send an email containing your
            tracking pixel and the activity
            will appear here.

        </div>


    <?php else: ?>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            Status
                        </th>

                        <th>
                            Recipient
                        </th>

                        <th>
                            Tracking ID
                        </th>

                        <th>
                            Opened
                        </th>

                        <th>
                            IP
                        </th>

                        <th>
                            Device / Browser
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php foreach ($records as $record): ?>


                    <tr>


                        <td>

                            <span class="status">
                                OPENED
                            </span>

                        </td>


                        <td>

                            <div class="email">

                                <?= clean(
                                    $record['email']
                                ) ?>

                            </div>

                        </td>


                        <td>

                            <span class="tracking-id">

                                <?= clean(
                                    $record['id']
                                ) ?>

                            </span>

                        </td>


                        <td>

                            <?= clean(
                                $record['time']
                            ) ?>

                        </td>


                        <td>

                            <span class="ip">

                                <?= clean(
                                    $record['ip']
                                ) ?>

                            </span>

                        </td>


                        <td>

                            <div style="
                                max-width:280px;
                                overflow:hidden;
                                text-overflow:ellipsis;
                                white-space:nowrap;
                            ">

                                <?= clean(
                                    $record['user_agent']
                                ) ?>

                            </div>

                        </td>


                    </tr>


                <?php endforeach; ?>


                </tbody>

            </table>

        </div>


    <?php endif; ?>


</div>


</main>


<div class="footer">

    JATINDRA MEHER — DECK CADET

</div>


</body>

</html>
