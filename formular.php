<?php

/**
 * PHP SKRIPT PRO ZPRACOVÁNÍ FORMULÁŘE
 * Soubor: formular.php
 * Pro prostředí FORPSI (používá standardní PHP mail)
 */

// 1. KONFIGURACE E-MAILU
$to = "info@svetrolet.cz";
$subject = "NOVÁ POPTÁVKA: Solární rolety ze SvětRolet.cz";
$from_email = "lead@svetrolet.cz";
$reply_to = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : $from_email;

// 2. ADRESY PRO PŘESMĚROVÁNÍ
$success_page = "dekujeme.html";
$error_page = "chyba.html"; // Můžete vytvořit jednoduchou stránku pro chybu

// Kontrola, zda formulář byl odeslán metodou POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Přesměrování, pokud někdo přistupuje k souboru přímo
    header("Location: index.html");
    exit;
}

// 3. ZPRACOVÁNÍ DAT
$jmeno = trim(strip_tags($_POST['jmeno']));
$email = trim(strip_tags($_POST['email']));
$telefon = trim(strip_tags($_POST['telefon']));
$poznamky = trim(strip_tags($_POST['poznamky']));
$predvrtani = trim(strip_tags($_POST['predvrtani']));
$souhlas = isset($_POST['souhlas']) ? "ANO" : "NE (Chyba validace!)";

// Zpracování vícenásobných polí pro rolety (sirka[], vyska[], atd.)
$roles_data = '';
if (!empty($_POST['sirka']) && is_array($_POST['sirka'])) {
    foreach ($_POST['sirka'] as $key => $value) {
        // Kontrola, zda existují i další související pole pro daný index
        if (
            isset($_POST['vyska'][$key]) && isset($_POST['pocet'][$key]) &&
            isset($_POST['barva_boxu'][$key]) && isset($_POST['barva_pancire'][$key])
        ) {
            $roles_data .= "--- Roleta č. " . ($key + 1) . " ---\n";
            $roles_data .= "  Šířka: " . trim(strip_tags($value)) . " mm\n";
            $roles_data .= "  Výška: " . trim(strip_tags($_POST['vyska'][$key])) . " mm\n";
            $roles_data .= "  Počet ks: " . trim(strip_tags($_POST['pocet'][$key])) . "\n";
            $roles_data .= "  Barva boxu: " . trim(strip_tags($_POST['barva_boxu'][$key])) . "\n";
            $roles_data .= "  Barva vodítek: " . trim(strip_tags($_POST['barva_voditek'][$key])) . "\n";
            $roles_data .= "  Barva pancíře: " . trim(strip_tags($_POST['barva_pancire'][$key])) . "\n";
            $roles_data .= "  Barva koncové lišty: " . trim(strip_tags($_POST['barva_listy'][$key])) . "\n\n";
        }
    }
}

// Zpracování ovladačů (nastavení 0, pokud nebylo zadáno)
$ovladace_data = "--- Poptávané ovladače ---\n";
$ovladace = [
    'Dálkový 1 roleta' => $_POST['ovladac_1'] ?? 0,
    'Dálkový 5 rolet' => $_POST['ovladac_5'] ?? 0,
    'Dálkový 15 rolet' => $_POST['ovladac_15'] ?? 0,
    'Nástěnný ovladač' => $_POST['ovladac_zed'] ?? 0,
    'Smart Hub' => $_POST['smart_hub'] ?? 0,
];

foreach ($ovladace as $key => $value) {
    $count = (int) $value;
    if ($count > 0) {
        $ovladace_data .= "  $key: $count ks\n";
    }
}
if (strlen($ovladace_data) == 30) { // Zůstala jen hlavička
    $ovladace_data .= "  Nezadány žádné ovladače.\n";
}


// 4. SESTAVENÍ E-MAILU
$body = "Dobrý den,\n\nobdrželi jste novou poptávku ze stránek svetrolet.cz. Detaily poptávky jsou následující:\n\n";
$body .= "=======================================\n";
$body .= "KONTAKTNÍ ÚDAJE\n";
$body .= "=======================================\n";
$body .= "Jméno: $jmeno\n";
$body .= "E-mail: $email\n";
$body .= "Telefon: $telefon\n\n";
$body .= "=======================================\n";
$body .= "SPECIFIKACE ROLET\n";
$body .= "=======================================\n";
if ($roles_data) {
    $body .= $roles_data;
} else {
    $body .= "Žádné rolety nebyly zadány.\n\n";
}
$body .= "=======================================\n";
$body .= "OVLADAČE A MONTÁŽ\n";
$body .= "=======================================\n";
$body .= $ovladace_data . "\n";
$body .= "Předvrtání lišt: " . ($predvrtani ? $predvrtani : 'Nezadáno') . "\n\n";
$body .= "Poznámky:\n" . ($poznamky ? $poznamky : '---') . "\n\n";
$body .= "Souhlas se zpracováním: $souhlas\n";
$body .= "=======================================\n";


// 5. HLAVIČKY A ODESLÁNÍ
$headers = "From: Svět Rolet <$from_email>\r\n";
$headers .= "Reply-To: $reply_to\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Základní validace (jméno a email jsou povinné)
if (empty($jmeno) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: $error_page");
    exit;
}

// Odeslání e-mailu
if (mail($to, $subject, $body, $headers)) {
    // Úspěch: Přesměrování na děkovnou stránku
    header("Location: $success_page");
    exit;
} else {
    // Chyba odeslání e-mailu (např. chyba konfigurace na serveru)
    header("Location: $error_page");
    exit;
}

?>