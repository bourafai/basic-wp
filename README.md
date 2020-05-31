>Ce plugin est la réponse à un exercise proposé par la société Tigersun media group
le plugin possède toutes les fonctionnalitées demandées

### Notes : 
- le plugin utilise les fonctionnalités du plugin Advanced Custom Fields, donc il faut l'installer et l'activer d'abord
- le plugin est optimisé visuellement pour le theme par défaut de wordpress twentynineteen


## Installation et utilisation :
#### Les livrables
vous trouvez dans le dossier "livrables" :
- La base de données (sql) .
- Le fichier d'export des données du site (xml) (tous les articles et medias).
- Les medias que j'ai utilisé lors du développement.
- Les dépendences (le plugin ACF & le theme twentynineteen).
- Le plugin à importer et à installer.

#### L'installation
dans votre site wordpress déja installé.
1. Installez et activez le theme Twentynineteen.
1. Installer et activez le plugin advanced-custom-fields.
1. Installer et activez le plugin final (ab-posts-render).
1. Importer le fichier XML des exports WordPress (pour ajouter les articles).
1. Dans votre dossier 'content/uploads', ajoutez les médias.
1. Allez dans Articles et modifiez les articles importés précédemment.
1. Ajoutez des titres altérnatives.


# Demande : 
###Préambule :

Notre évaluation prendra en compte la partie fonctionnelle, le respect des maquettes et les contraintes techniques proposées. Les bonnes pratiques de développement seront fortement appréciées.
Contraintes techniques : CMS WordPress, Développement en POO.
Vous devrez disposer d’un serveur web, une base de données MySQL et un projet WordPress.
Documents attendus : Un lien GitHub contenant un fichier d’export de la base de données et le plugin WordPress réalisé.
Si besoin, n’hésitez pas à y intégrer un fichier README.

Vous devez créer un plugin qui ajoute ces 5 options lors de l’ajout d’un articles lambda
Nous appellerons « Article Variant Generator »

##Création du Plugin :

Afin de mener à bien cette exercice, vous devez créer 5 articles au minimum ( avec les fonctionnalité du plugin )

Voici les fonctionnalités du plugin :


### 1.Titre Options :

Chaque articles disposera grâce au plugin de 5 variantes de titres mais un seul sera affiché, à la fois, on l’appellera « Titre Charger »

L’affichage du « Titre Charger » sera aléatoire  ce qui veut dire qu'au chargement de la page de l’article, un titre sera choisi parmi les 5 variantes.

Le contenue de l’article sera du lorem ipsum et n’a aucune importance.
Il pourra néanmoins, si vous le souhaitez être différent ou le même sur tous les articles.

>Astuce : Vous pouvez choisir de mettre juste titre1, titre2, titre3, titre 4, titre 5 sur tous vos articles. Néanmoins si vous êtes motivé, choisissez de bien différencier les titres avec les articles pour plus de clarté.
Article 1 sur les tomates (variantes titres) : Titre 1 tomates, Titre 2 tomates…etc
Article 2 sur les voiture (variantes titres) : Titre 1 voiture, Titre 2 voiture…etc
…..

### 2.Template Options :

Chaque articles aura aussi 3 variantes de templates comme les titres, un seul template sera affiché, à la fois, on l’appellera «  Template Charger » 

Toujours comme les titres, le « Template Charger » sera choisi de façon aléatoire au chargement de la page.

Ce variantes affiche la pub de façon différentes, avec des contraintes lié au front. ( Voir les screen plus bas )

- Template 1 : la pub n'apparaît que lorsque le texte de l’article est visible.

- Template 2 : la pub apparaît en sticky en haut de page lorsqu’on scroll vers le haut et en bas de page quand on scroll vers le bas.

- Template 3 : la pub du sidebar doit suivre au scroll mais ne doit pas dépasser la fin de l’article.

### 3.Bouton de navigation ‘Prev’ & ‘Next’ options :

Sur les articles ces boutons permettent de naviguer plus facilement entre les articles.

- Next : Si on clique on arrive sur un autre articles choisi de façon aléatoire, ainsi que son titre & templates

- Prev : Si on clique dessus contrairement au bouton Prev on reviens sur l’article précédent, il n’y donc pas d’affichage aléatoire. Nous devons donc retourner sur l’article exacte avec son titre et template affiché précédemment. Les suggestions quand à elle n’on pas besoin d’être identiques sont affiché toujours de façons aléatoire.

### 4.Suggestions options :

Sur les articles une rubriques de suggestions affichera 3 vignettes d’articles avec un titre. Ces vignettes devront elles aussi être affichées de façon aléatoire tant au niveau de l’ordre et des titres ( déjà vu plus haut ).

> Astuce : Il sera peut être préférable de choisir une image différente pour chaque articles afin que vos suggestions soit mieux différentiable au premier coup d’oeil

### 5.Url options :

L’url servira de Key ce qui nous permettra de différencier les articles entre eux et leurs variantes
L’url devra comporter ces infos :
Titre ( exemple titre=2 )
Template ( exemple tp=1 )
Id de l’articles ( exemple 4582 )
Ce qui nous donne :
Exemple : https://www.domain.com/4582?titre=2&tp=1

Elle nous permettra donc d’accéder à l’article ainsi qu’a sa variante

Ce qui n’a aucune importance : 

- L’auteur n’a pas d’importance il doit/peut donc être le même pour chaque articles
- L’image n'a aucune importance, Vous choisirez l’image que vous voulez ou vous pouvez gardez la même image.
- Les tags ou catégories n’ont aucune importance et doivent/peuvent être propre à chaque articles.
- Le menu, les liens peuvent redirigé au même endroit

