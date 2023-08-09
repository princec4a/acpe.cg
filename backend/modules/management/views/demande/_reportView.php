<div style='width: 100%; font-size: 13px;'>
    <div style='float: left; width: 30%;padding: 0;margin: 0;'><img src="<?=$logoUrl?>" style="width: 90%;"></div>
    <div style='float: left; width: 45%; height: 10%; border-top: 1px solid #ffffff;'>
        <div style="margin-top: 10%; height: 5%;">&nbsp;</div>
    </div>
    <div style='float: left; width: 25%;margin: 0; text-align: right;'>
        <img src="<?=substr($model->employe->photo, 13)?>" style='margin: 10px;border: 1px solid #000; height: 60%;'>
    </div>
    <div style='float: left; width: 70%; text-transform: uppercase; text-align: center;font-weight: bold; font-size: 16px;'><?=$model->typeAutorisation->libelle?></div>
    <div style='float: left; width: 30%; color: #ffffff'>&nbsp;</div>
    <div style='float: left; width: 100%'>&nbsp;</div>

    <div style='float: left; width: 68%; margin: 0 0 10px 0;'>
        <label>Société</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px;'><?=$model->employe->entreprise->raison_sociale?></div>
    </div>
    <div style='float: left; width: 2%;'>&nbsp;</div>
    <div style='float: left; width: 30%;margin: 0 0 10px 0;'>
        <label>RCCM</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px; font-size: 12px;'><?=$rccm?></div>
    </div>

    <div style='float: left; width: 30%;margin: 0 0 10px 0;'>
        <label>NIU</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$niu?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 36%; margin: 0 0 10px 0;'>
        <label>Email</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->entreprise->email?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 30%'>
        <label>Téléphone(s)</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$telephones?></div>
    </div>

    <div style='float: left; width: 68%'>
        <label>Adresse au Congo</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->entreprise->adresse_congo?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 30%'>
        <label>Ville</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$ville?></div>
    </div>
    <div style='float: left; width: 100%; margin: 5px 0; font-weight: bold;'>Nous sollicitions <?=$model->typeAutorisation->libelle?> pour : </div>

    <div style='float: left; width: 10%; margin: 0 0 10px 0;'>
        <label>Civilité</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=($model->employe->sexe == 'HOMME')? 'Monsieur' : 'Madame' ?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 41%'>
        <label>Nom(s)</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->nom?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 45%'>
        <label>Prénom(s)</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->prenom?></div>
    </div>

    <div style='float: left; width: 20%;'>
        <label>Date de naissance</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?= $model->employe->date_naissance?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 31%'>
        <label>Lieu de naissance</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->lieu_naissance?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 45%'>
        <label>Nationalité</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->nationalite0->nompays?></div>
    </div>
    <div style='float: left; width: 100%'>&nbsp;</div>
    <div style='float: left; width: 25%'>
        <label>Type de la pièce d'identité</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->kitPersonneIdentites[0]->typePiece->code?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 29%'>
        <label>Numéro de la pièce</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->kitPersonneIdentites[0]->numero?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 20%'>
        <label>Date d'émission</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->kitPersonneIdentites[0]->date_emission?></div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 20%'>
        <label>Date d'expiration</label>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'><?=$model->employe->kitPersonneIdentites[0]->date_expiration?></div>
    </div>
    <div style='float: left; width: 100%; margin: 5px 0 0 0;'>En qualité de : </div>
    <div style='float: left; width: 45%'>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'>&nbsp;</div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 25%'>
        <div style='width: 100%; padding-left: 5px; text-align: right;'>Avis de non objection du : </div>
    </div>
    <div style='float: left; width: 2%'>&nbsp;</div>
    <div style='float: left; width: 25%'>
        <div style='width: 100%; border: 1px solid #000; padding-left: 5px'>&nbsp;</div>
    </div>
    <div style='float: left; width: 100%; text-align: center;text-transform: uppercase; margin: 10px 0 3px 0;'>Espace réservé à ACPE</div>
    <div style='float: left; width: 48%; border: 1px solid #000; font-size: 12px;margin: 0 0 10px 0; height: 17%; padding: 1px 0;'>
        <?php foreach($model->kitElementDemandes as $k=>$item) : ?>
            <div style="float: left; width: 10%;  margin: 5px 0 5px 3px;">
                <input type="radio" name="num_languages" value="2" >
            </div>
            <div style="float: left; width: 80%; margin: 0px 0 2px 3px;"><?=$item->pieceFournir->nom?></div>
        <?php endforeach;?>
    </div>
    <div style='float: left; width: 2%;'>&nbsp;</div>
    <div style='float: left; width: 48%; border: 1px solid #000;'>
        <div style='display: block; text-align: center; height: 17%;'>OBSERVATIONS</div>
    </div>

    <div style='float: left; width: 17%; font-size: 11px;'>N° DE LA DEMANDE</div>
    <div style='float: left; width: 29%; border: 1px solid #000; text-align: center; font-weight: bold;'><?=$model->code?></div>
    <div style='float: left; width: 23%; font-size: 12px; text-transform: uppercase; text-align: right; padding-right: 5px;'>agent vérificateur</div>
    <div style='float: left; width: 29%; border: 1px solid #000;'>&nbsp;</div>
    <div style='float: left; width: 100%;'></div>

    <div style='float: left; width: 33%;border: 1px solid #000;height: 5%; margin: 10px 0 0 0; padding: 0 0 5px 0;'>
        <div style='display: block; text-align: center; font-weight: bold; font-size: 11px'>Responsable de l'entreprise</div>
        <div style='display: block;text-align: center; font-size: 9px; height: 15%'>Signature et cachet</div>
        <div style='display: block; font-size: 11px; text-align: center;'>
            <div>Déposée le</div>
            <div style='border: 1px solid #000; width: 50%; margin: 0 auto;'>&nbsp;</div>
        </div>
    </div>
    <div style='float: left; width: 33%;border: 1px solid #000;height: 5%; padding: 0 0 5px 0;'>
        <div style='display: block; text-align: center; font-weight: bold; font-size: 11px'>Agent vérificateur</div>
        <div style='display: block;text-align: center; font-size: 9px; height: 15%'>Signature</div>
        <div style='display: block; font-size: 11px; text-align: center;'>
            <div>Déposée le</div>
            <div style='border: 1px solid #000; width: 50%; margin: 0 auto;'>&nbsp;</div>
        </div>
    </div>
    <div style='float: left; width: 33%;border: 1px solid #000; height: 5%; padding: 0 0 5px 0;'>
        <div style='display: block; text-align: center;  font-weight: bold; font-size: 11px'>Chef d'agence</div>
        <div style='display: block;text-align: center; font-size: 9px; height: 15%'>Signature et observations</div>
        <div style='display: block; font-size: 11px; text-align: center;'>
            <div>Déposée le</div>
            <div style='border: 1px solid #000; width: 50%; margin: 0 auto;'>&nbsp;</div>
        </div>
    </div>
</div>
