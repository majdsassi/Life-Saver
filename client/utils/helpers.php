<?php

function get_client_dons(?string $statut = null, string $user_id)
{
    global $pdo;

    $baseQuery = "
        SELECT c.nom_centre, d.date_don, d.volume_ml, d.statut, ds.groupe_sanguin
        FROM centres_collecte c
        JOIN dons d    ON c.id_centre = d.id_centre
        JOIN donneurs ds ON d.id_donneur = ds.id_donneur
        WHERE d.id_donneur = ?
    ";

    if ($statut !== null) {
        $baseQuery .= " AND d.statut = ? ";
    }

    $baseQuery .= " ORDER BY d.date_don DESC";

    $stmt = $pdo->prepare($baseQuery);

    if ($statut !== null) {
        $stmt->execute([$user_id, $statut]);
    } else {
        $stmt->execute([$user_id]);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



