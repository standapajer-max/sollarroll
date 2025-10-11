======================================================
IMPLEMENTACE WEBU "SVĚT ROLET" NA HOSTINGU FORPSI
======================================================

Tento dokument obsahuje přesný postup pro nahrání a zprovoznění webové prezentace a poptávkového formuláře na webhostingu Forpsi.

------------------------------------------------------
KROK 1: PŘÍPRAVA SOUBORŮ
------------------------------------------------------

Ujistěte se, že máte připraveny všechny následující soubory, které by měly být umístěny ve stejné složce:

1.  index.html        (Hlavní stránka webu s formulářem)
2.  formular.php      (PHP skript pro odesílání e-mailů)
3.  dekujeme.html     (Stránka po úspěšném odeslání)
4.  chyba.html        (Stránka pro případ chyby)
5.  Všechny obrázky (.png, .jpg) uvedené v souboru SEZNAM_FILES.TXT (logo, roletarez, vzor, barvy, galerie...).

------------------------------------------------------
KROK 2: NAHRÁNÍ SOUBORŮ NA FORPSI
------------------------------------------------------

1.  **Připojení k FTP:** Připojte se ke svému webovému prostoru na Forpsi pomocí FTP klienta (např. FileZilla). Přístupové údaje naleznete v administraci Forpsi.
2.  **Cílový adresář:** Všechny soubory (HTML, PHP i obrázky) nahrajte do **kořenového adresáře** vaší domény. Obvykle to je složka s názvem `www` nebo `httpdocs`.
3.  **Ověření:** Zkontrolujte, zda jsou soubory `index.html`, `formular.php`, `dekujeme.html` a `chyba.html` přímo v tomto hlavním adresáři.

------------------------------------------------------
KROK 3: NASTAVENÍ E-MAILOVÉ FUNKČNOSTI (KRITICKÉ)
------------------------------------------------------

Formulář je nastaven tak, že odesílá e-mail z adresy `lead@svetrolet.cz` na adresu `info@svetrolet.cz`.

1.  **Vytvoření e-mailových schránek:** Ujistěte se, že schránky **`lead@svetrolet.cz`** a **`info@svetrolet.cz`** skutečně existují a jsou aktivní ve správě e-mailů u Forpsi.
    * *Důvod:* Hostingy (včetně Forpsi) často vyžadují, aby e-mail použitý v hlavičce `From:` (odesílatel) existoval v rámci hostované domény. Toto je klíčové pro správné doručení e-mailu a pro snížení rizika, že poptávky skončí ve spamu.

2.  **Kontrola PHP skriptu:** V souboru `formular.php` jsou tyto adresy již nastaveny v sekci Konfigurace E-mailu:
    ```php
    $to = "info@svetrolet.cz";
    $from_email = "lead@svetrolet.cz";
    ```
    Pokud byste adresy v budoucnu měnili, musíte je změnit zde.

------------------------------------------------------
KROK 4: TESTOVÁNÍ FUNKČNOSTI
------------------------------------------------------

1.  **Test hlavní stránky:** Zadejte název své domény do prohlížeče (`https://vasedomena.cz`). Měla by se zobrazit celá stránka.
2.  **Test formuláře:** Přejděte na sekci Poptávkový formulář, vyplňte všechna povinná pole (Jméno, E-mail, Telefon, Souhlas) a zadejte minimálně jednu roletu.
3.  **Odeslání a kontrola:**
    * Klikněte na "Odeslat poptávku".
    * Pokud je vše v pořádku, měli byste být přesměrováni na stránku **`dekujeme.html`**.
    * Zkontrolujte schránku **`info@svetrolet.cz`**, zda dorazila poptávka.
    * *Pokud dojde k chybě, budete přesměrováni na **`chyba.html`**.* V takovém případě zkontrolujte konfiguraci e-mailových schránek a PHP kód.

------------------------------------------------------
DOPORUČENÍ PRO BUDOUCÍ ÚDRŽBU
------------------------------------------------------

* **Zálohování:** Pravidelně zálohujte soubory webu.
* **Bezpečnost:** Ačkoli je použitá metoda `mail()` standardní a pro jednoduché formuláře postačuje, pro robustnější řešení zvažte v budoucnu použití knihoven jako PHPMailer pro lepší logování a bezpečnější odesílání přes SMTP server.