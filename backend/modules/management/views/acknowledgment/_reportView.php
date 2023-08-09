<div id="page">
    <div id="header">
        <div style='float: left; width: 70%;padding: 0;margin: 0;'><img src="<?=$logoUrl?>" style="width: 40%;"></div>
        <div id="right" style="float: left; text-align: right">
            <p style="text-align: center; font-size: 12px"><b>REPUBLIQUE DU CONGO</b> <br/>Unité*Travail*Progrès</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
        </div>
    </div>
    <div style="clear: both"></div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div id="subject" style=" font-size: 16px;">
        <p><span style="font-weight: bold;">Objet</span> : <?=$subject?></p>
        <p><span style="font-weight: bold;">Société</span> : <?=$enterprise?></p>
        <p><span style="font-weight: bold;">Nom de l’employé</span> : <?=$employe?></p>
        <p><span style="font-weight: bold;">Fonction occupée</span> : <?=$poste?></p>
    </div>
    <p>&nbsp;</p>
    <div id="table">
        <table border="1" style="border-collapse: collapse;" width="100%">
            <tr style="background-color: #0B77BD">
                <th style="text-align: center; color: #fff">N°</th>
                <th style="text-align: center; color: #fff">Intitulés des pièces fournies</th>
                <th style="text-align: center; color: #fff">Nombre</th>
                <th style="text-align: center; color: #fff">Observations</th>
            </tr>
            <?php $i = 1 ?>
            <?php foreach($pieceFournies as $item) : ?>
                <tr>
                    <td style="text-align: center"><?=$i?></td>
                    <td valign="top" style="text-align: justify; padding: 5px"><?=$item->pieceFournie->nom?></td>
                    <td valign="top" style="text-align: center; padding: 5px"><?=$item->nombre?></td>
                    <td valign="top" style="text-align: justify; padding: 5px">&nbsp;</td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        </table>
    </div>
    <p>&nbsp;</p>
    <div id="footer">
        <p style="text-align: right; line-height: 2; font-size: 16px;"><?=$footer?></p>
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div id="signature" style="position: relative; bottom: 0;">
        <div style="float: left; width: 50%;font-size: 16px;" >Le demandeur</div>
        <div style="float: left; text-align: right; font-size: 16px;">Agence départementale ACPE</div>
        <div style="clear: both"></div>
    </div>
</div>