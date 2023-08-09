<div id="page">
    <div id="header">
        <div style='float: left; width: 70%;padding: 0;margin: 0;'><img src="<?=$logoUrl?>" style="width: 40%;"></div>
        <div id="right" style="float: left; text-align: right">
            <p style="text-align: center; font-size: 12px"><b>REPUBLIQUE DU CONGO</b> <br/>Unité*Travail*Progrès</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p style="text-align: center; font-size: 16px"><?=$header?></p>
        </div>
    </div>
    <div style="clear: both"></div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div id="receiver" style=" font-size: 16px;">
        <p style="text-align: center"><?=$receiver?></p>
    </div>
    <p>&nbsp;</p>
    <div id="subject" style=" font-size: 16px;">
        <p><span style="text-decoration: underline; font-weight: bold;">Objet</span> : <?=$subject?></p>
    </div>
    <p>&nbsp;</p>
    <div id="headerContent" >
        <p style="text-align: justify; font-size: 16px;"><?=$headerContent?></p>
    </div>
    <p>&nbsp;</p>
    <div id="bodyContent">
        <p style="text-align: justify; line-height: 2; font-size: 16px;"><?=$bodyContent?></p>
    </div>
    <div id="table">
        <table border="1" style="border-collapse: collapse;">
            <tr style="background-color: #0B77BD">
                <th style="text-align: center; color: #fff">N°</th>
                <th style="text-align: center; color: #fff">Sociétés</th>
                <th style="text-align: center; color: #fff">Nature du dossier</th>
                <th style="text-align: center; color: #fff">Noms et Prénoms</th>
                <th style="text-align: center; color: #fff">Motifs du rejet</th>
            </tr>
            <tr>
                <td style="text-align: center">1</td>
                <td valign="top" style="text-align: justify; padding: 5px"><?=$model->employe->entreprise->raison_sociale?></td>
                <td valign="top" style="text-align: justify; padding: 5px"><?=$model->typeAutorisation->libelle?></td>
                <td valign="top" style="text-align: justify; padding: 5px"><?=$model->employe->nom .' '.$model->employe->prenom?></td>
                <td valign="top" style="text-align: justify; padding: 5px"><?=$motif?></td>
            </tr>
        </table>
    </div>
    <p>&nbsp;</p>
    <div id="greeting">
        <p style="text-align: justify; line-height: 2; font-size: 16px;"><?=$greeting?></p>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div id="footer">
        <p style="text-align: right; line-height: 2; font-size: 16px;"><?=$footer?></p>
        <p style="text-align: right; line-height: 2; font-size: 16px;"><strong><?=$director?></strong></p>
    </div>
</div>