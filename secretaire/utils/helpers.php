<?php

function get_all_donneurs()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM donneurs ORDER BY nom ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_donneur_by_id($id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM donneurs WHERE id_donneur = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_all_users($role = null)
{
    global $pdo;

    $baseQuery = "SELECT u.*, c.nom_centre
                  FROM utilisateurs u
                  LEFT JOIN centres_collecte c ON u.id_centre = c.id_centre";

    if ($role === null) {
        $stmt = $pdo->query($baseQuery . " ORDER BY u.nom_utilisateur ASC");
    } else {
        $stmt = $pdo->prepare($baseQuery . " WHERE u.role = ? ORDER BY u.nom_utilisateur ASC");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_by_id($id)
{
    global $pdo;

    $stmt = $pdo->prepare(
        "SELECT u.*, c.nom_centre
         FROM utilisateurs u
         LEFT JOIN centres_collecte c ON u.id_centre = c.id_centre
         WHERE u.id_utilisateur = ?"
    );
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_all_centres(bool $withDetails = false)
{
    global $pdo;

    if ($withDetails) {
        $stmt = $pdo->query("SELECT id_centre, nom_centre, ville FROM centres_collecte ORDER BY nom_centre ASC");
    } else {
        $stmt = $pdo->query("SELECT id_centre, nom_centre FROM centres_collecte ORDER BY nom_centre ASC");
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_centre_by_id($id)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT id_centre, nom_centre, ville FROM centres_collecte WHERE id_centre = ?");
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_don_by_id($id)
{
    global $pdo;

    $stmt = $pdo->prepare(
        "SELECT d.*, dn.nom, dn.prenom, c.nom_centre
         FROM dons d
         INNER JOIN donneurs dn ON d.id_donneur = dn.id_donneur
         LEFT JOIN centres_collecte c ON d.id_centre = c.id_centre
         WHERE d.id_don = ?"
    );
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_dons(?string $statut = null)
{
    global $pdo;

    $baseQuery = "SELECT d.*, dn.nom, dn.prenom, c.nom_centre
                  FROM dons d
                  INNER JOIN donneurs dn ON d.id_donneur = dn.id_donneur
                  LEFT JOIN centres_collecte c ON d.id_centre = c.id_centre";

    if ($statut !== null) {
        $stmt = $pdo->prepare($baseQuery . " WHERE d.statut = ? ORDER BY d.date_don DESC");
        $stmt->execute([$statut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $stmt = $pdo->query($baseQuery . " ORDER BY d.date_don DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

