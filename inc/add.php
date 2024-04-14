<?php


use MongoDB\BSON\ObjectId;

$mdb = new myDbClass();

$client = $mdb->getClient();
$movies_collection = $mdb->getCollection('movies');
$confirm = GETPOST('confirm_envoyer');
if ($confirm == 'Envoyer') {

    use JsonSchema\Validator;
    use JsonSchema\Constraints\Constraint;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $realisateurs = $_POST['realisateurs'];
    $producteurs = $_POST['producteurs'];
    $acteurs_principaux = $_POST['acteurs_principaux'];
    $synopsis = $_POST['synopsis'];

    $data = [
        "title" => $title,
        "year" => $year,
        "realisateurs" => $realisateurs,
        "producteurs" => $producteurs,
        "acteurs_principaux" => $acteurs_principaux,
        "synopsis" => $synopsis
    ];

    $schema = (object)[
        "type" => "object",
        "properties" => (object)[
            "title" => (object)["type" => "string"],
            "year" => (object)["type" => "string"],
            "realisateurs" => (object)["type" => "string"],
            "producteurs" => (object)["type" => "string"],
            "acteurs_principaux" => (object)["type" => "string"],
            "synopsis" => (object)["type" => "string"]
        ],
        "required" => ["title", "year", "realisateurs", "producteurs", "acteurs_principaux", "synopsis"]
    ];

    $validator = new Validator;
    $validator->validate($data, $schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

    if ($validator->isValid()) {
        $myDb = new myDbClass();
        $insertedId = $myDb->insertOne('myCollection', $data);
        header("Location: index.php");
        exit();
    } else {
        echo "JSON does not validate. Violations:\n";
        foreach ($validator->getErrors() as $error) {
            echo sprintf("[%s] %s\n", $error['property'], $error['message']);
        }
    }
}
}
?>
<div class="dtitle w3-container w3-teal">
    <h2>Ajout d'un nouvel element</h2>
</div>
<form class="w3-container" action="index.php?action=add" method="POST">
    <div class="dcontent">
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="title"><b>Titre</b></label>
                <input class="w3-input w3-border" type="text" id="title" name="title" />
            </div>
            <div class="w3-half">
                <label class="w3-text-blue" for="year"><b>Année de sortie</b></label><br />
                <input type="text" id="year" name="year" />
            </div>
        </div>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="realisateurs"><b>Réalisateurs</b></label>
                <textarea class="w3-input w3-border" id="realisateurs" name="realisateurs"></textarea>
            </div>
            <div class="w3-half">
                <label class="w3-text-blue" for="producteurs"><b>Producteurs</b></label>
                <textarea class="w3-input w3-border" id="producteurs" name="producteurs"></textarea>
            </div>
        </div>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label class="w3-text-blue" for="acteurs_principaux"><b>Acteurs Principaux</b></label>
                <textarea class="w3-input w3-border" id="acteurs_principaux" name="acteurs_principaux"></textarea>
            </div>
        </div>
        <label class="w3-text-blue" for="synopsis"><b>Synopsis</b></label>
        <textarea class="w3-input w3-border" id="synopsis" name="synopsis"></textarea>
        <br />
        <div class="w3-row-padding">
                <div class="w3-half">
                    <input class="w3-btn w3-red" type="submit" name="cancel" value="Annuler" />
                </div>
                <div class="w3-half">
                    <input class="w3-btn w3-blue-grey" type="submit" name="confirm_envoyer" value="Envoyer" />
                </div>
        </div>
        <br /><br />
</form>
</div>
<div class="dfooter">
</div>