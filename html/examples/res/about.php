<?php
//SHOPROOO商城资源
echo "<style type=\"text/css\">\n<!--\n    table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }\n    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}\n    div.note {border: solid 1mm #DDDDDD;background-color: #EEEEEE; padding: 2mm; border-radius: 2mm; width: 100%; }\n    ul.main { width: 95%; list-style-type: square; }\n    ul.main li { padding-bottom: 2mm; }\n    h1 { text-align: center; font-size: 20mm}\n    h3 { text-align: center; font-size: 14mm}\n-->\n</style>\n<page backtop=\"14mm\" backbottom=\"14mm\" backleft=\"10mm\" backright=\"10mm\" style=\"font-size: 12pt\">\n    <page_header>\n        <table class=\"page_header\">\n            <tr>\n                <td style=\"width: 50%; text-align: left\">\n                    A propos de ...\n                </td>\n                <td style=\"width: 50%; text-align: right\">\n                    HTML2PDF v";
echo __CLASS_HTML2PDF__;
echo "                </td>\n            </tr>\n        </table>\n    </page_header>\n    <page_footer>\n        <table class=\"page_footer\">\n            <tr>\n                <td style=\"width: 33%; text-align: left;\">\n                    http://html2pdf.fr/\n                </td>\n                <td style=\"width: 34%; text-align: center\">\n                    page [[page_cu]]/[[page_nb]]\n                </td>\n                <td style=\"width: 33%; text-align: right\">\n                    &copy;Spipu 2008-2011\n                </td>\n            </tr>\n        </table>\n    </page_footer>\n    <bookmark title=\"Présentation\" level=\"0\" ></bookmark>\n    <br><br><br><br><br><br><br><br>\n    <h1>HTML2PDF</h1>\n    <h3>v";
echo __CLASS_HTML2PDF__;
echo "</h3><br>\n    <br><br><br><br><br>\n    <div style=\"text-align: center; width: 100%;\">\n        <br>\n        <img src=\"./res/logo.png\" alt=\"Logo HTML2PDF\" style=\"width: 150mm\">\n        <br>\n    </div>\n    <br><br><br><br><br>\n    <div class=\"note\">\n        HTML2PDF est un convertisseur de code HTML vers PDF écrit en PHP5, utilisant la librairie <a href=\"http://tcpdf.org\">TCPDF.</a><br>\n        <br>\n        Il permet la conversion d'HTML et d'xHTML valide au format PDF, et est distribué sous licence LGPL.<br>\n        <br>\n        Cette librairie a été conçue pour gérer principalement les TABLE imbriquées afin de générer des factures, bon de livraison, et autres documents officiels.<br>\n        <br>\n        Vous pouvez télécharger la dernière version de HTML2PDF ici : <a href=\"http://html2pdf.fr/\">http://html2pdf.fr/</a>.<br>\n    </div>\n</page>\n<page pageset=\"old\">\n    <bookmark title=\"Sommaire\" level=\"0\" ></bookmark>\n    <!-- here will be the automatic index -->\n</page>\n<page pageset=\"old\">\n    <bookmark title=\"Compatibilité\" level=\"0\" ></bookmark>\n    <bookmark title=\"Balises HTML\" level=\"1\" ></bookmark>\n    <bookmark title=\"Balises classiques\" level=\"2\" ></bookmark>\n    <div class=\"note\">\n        La liste des balises HTML utilisables est la suivante :<br>\n    </div>\n    <br>\n    <ul class=\"main\">\n        <li>&lt;a&gt; : Ceci est un lien vers <a href=\"http://html2pdf.fr\">le site de HTML2PDF</a></li>\n        <li>&lt;b&gt;, &lt;strong&gt; : Ecrire en <b>gras</b>.</li>\n        <li>&lt;big&gt; : Ecrire plus <big>gros</big>.</li>\n        <li>&lt;br&gt; : Permet d'aller à la ligne</li>\n        <li>&lt;cite&gt; : <cite>Ceci est une citation</cite></li>\n        <li>&lt;code&gt;, &lt;pre&gt;</li>\n        <li>&lt;div&gt; :&nbsp;<div style=\"border: solid 1px #AADDAA; background: #DDFFDD; text-align: center; width: 50mm\">exemple de DIV</div></li>\n        <li>&lt;em&gt;, &lt;i&gt;, &lt;samp&gt; : Ecrire en <em>italique</em>.</li>\n        <li>&lt;font&gt;, &lt;span&gt; : <font style=\"color: #000066; font-family: times\">Exemple d'utilisation</font></li>\n        <li>&lt;h1&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;h4&gt;, &lt;h5&gt;, &lt;h6&gt;</li>\n        <li>&lt;hr&gt; : barre horizontale</li>\n        <li>&lt;img&gt; : <img src=\"./res/tcpdf_logo.jpg\" style=\"width: 10mm\"></li>\n        <li>&lt;p&gt; : Ecrire dans un paragraphe</li>\n        <li>&lt;s&gt; : Texte <s>barré</s></li>\n        <li>&lt;small&gt; : Ecrire plus <small>petit</small>.</li>\n        <li>&lt;style&gt;</li>\n        <li>&lt;sup&gt; : Exemple<sup>haut</sup>.</li>\n        <li>&lt;sub&gt; : Exemple<sub>bas</sub>.</li>\n        <li>&lt;u&gt; : Texte <u>souligné</u></li>\n        <li>&lt;table&gt;, &lt;td&gt;, &lt;th&gt;, &lt;tr&gt;, &lt;thead&gt;, &lt;tbody&gt;, &lt;tfoot&gt;, &lt;col&gt; </li>\n        <li>&lt;ol&gt;, &lt;ul&gt;, &lt;li&gt;</li>\n        <li>&lt;form&gt;, &lt;input&gt;, &lt;textarea&gt;, &lt;select&gt;, &lt;option&gt;</li>\n        <li>&lt;fieldset&gt;, &lt;legend&gt;</li>\n        <li>&lt;del&gt;, &lt;ins&gt;</li>\n        <li>&lt;draw&gt;, &lt;line&gt;, &lt;rect&gt;, &lt;circle&gt;, &lt;ellipse&gt;, &lt;polygone&gt;, &lt;polyline&gt;, &lt;path&gt;</li>\n    </ul>\n    <bookmark title=\"Balises spécifiques\" level=\"2\" ></bookmark>\n    <div class=\"note\">\n        Les balises spécifiques suivantes ont été ajoutées :<br>\n    </div>\n    <br>\n    <ul class=\"main\" >\n        <li>&lt;page&gt;</li>\n        <li>&lt;page_header&gt;</li>\n        <li>&lt;page_footer&gt;</li>\n        <li>&lt;nobreak&gt;</li>\n        <li>&lt;barcode&gt;</li>\n        <li>&lt;bookmark&gt;</li>\n        <li>&lt;qrcode&gt;</li>\n    </ul>\n</page>\n<page pageset=\"old\">\n    <bookmark title=\"Styles CSS\" level=\"1\" ></bookmark>\n    <div class=\"note\">\n        La liste des styles CSS utilisables est la suivante :<br>\n    </div>\n    <br>\n    <table style=\"width: 100%\">\n        <tr style=\"vertical-align: top\">\n            <td style=\"width: 50%\">\n                <ul class=\"main\">\n                    <li>color</li>\n                    <li>font-family</li>\n                    <li>font-weight</li>\n                    <li>font-style</li>\n                    <li>font-size</li>\n                    <li>text-decoration</li>\n                    <li>text-indent</li>\n                    <li>text-align</li>\n                    <li>text-transform</li>\n                    <li>vertical-align</li>\n                    <li>width</li>\n                    <li>height</li>\n                    <li>line-height</li>\n                    <li>padding</li>\n                    <li>padding-top</li>\n                    <li>padding-right</li>\n                    <li>padding-bottom</li>\n                    <li>padding-left</li>\n                    <li>margin</li>\n                    <li>margin-top</li>\n                    <li>margin-right</li>\n                    <li>margin-bottom</li>\n                    <li>margin-left</li>\n                    <li>position</li>\n                    <li>top</li>\n                    <li>bottom</li>\n                    <li>left</li>\n                    <li>right</li>\n                    <li>float</li>\n                    <li>rotate</li>\n                    <li>background</li>\n                    <li>background-color</li>\n                    <li>background-image</li>\n                    <li>background-position</li>\n                    <li>background-repeat</li>\n                </ul>\n            </td>\n            <td style=\"width: 50%\">\n                <ul class=\"main\">\n                    <li>border</li>\n                    <li>border-style</li>\n                    <li>border-color</li>\n                    <li>border-width</li>\n                    <li>border-collapse</li>\n                    <li>border-top</li>\n                    <li>border-top-style</li>\n                    <li>border-top-color</li>\n                    <li>border-top-width</li>\n                    <li>border-right</li>\n                    <li>border-right-style</li>\n                    <li>border-right-color</li>\n                    <li>border-right-width</li>\n                    <li>border-bottom</li>\n                    <li>border-bottom-style</li>\n                    <li>border-bottom-color</li>\n                    <li>border-bottom-width</li>\n                    <li>border-left</li>\n                    <li>border-left-style</li>\n                    <li>border-left-color</li>\n                    <li>border-left-width</li>\n                    <li>border-radius</li>\n                    <li>border-top-left-radius</li>\n                    <li>border-top-right-radius</li>\n                    <li>border-bottom-left-radius</li>\n                    <li>border-bottom-right-radius</li>\n                    <li>list-style</li>\n                    <li>list-style-type</li>\n                    <li>list-style-image</li>\n                </ul>\n            </td>\n        </tr>\n    </table>\n</page>\n<page pageset=\"old\">\n    <bookmark title=\"Propriétés\" level=\"1\" ></bookmark>\n    <div class=\"note\">\n        La liste des propriétés utilisables est la suivante :<br>\n    </div>\n    <br>\n    <table style=\"width: 100%\">\n        <tr style=\"vertical-align: top\">\n            <td style=\"width: 50%\">\n                <ul class=\"main\">\n                    <li>cellpadding</li>\n                    <li>cellspacing</li>\n                    <li>colspan</li>\n                    <li>rowspan</li>\n                    <li>width</li>\n                    <li>height</li>\n                </ul>\n            </td>\n            <td style=\"width: 50%\">\n                <ul class=\"main\">\n                    <li>align</li>\n                    <li>valign</li>\n                    <li>bgcolor</li>\n                    <li>bordercolor</li>\n                    <li>border</li>\n                    <li>type</li>\n                    <li>value</li>\n                </ul>\n            </td>\n        </tr>\n    </table>\n    <bookmark title=\"Limitations\" level=\"0\" ></bookmark>\n    <div class=\"note\">\n        Cette librairie comporte des limitations :<br>\n    </div>\n    <br>\n    <ul class=\"main\">\n        <li>Les float ne sont gérés que pour la balise IMG.</li>\n        <li>Elle ne permet généralement pas la conversion directe d'une page HTML en PDF, ni la conversion du résultat d'un WYSIWYG en PDF.</li>\n        <li>Cette librairie est là pour faciliter la génération de documents PDF, pas pour convertir n'importe quelle page HTML.</li>\n        <li>Les formulaires ne marchent pas avec tous les viewers PDFs...</li>\n        <li>Lisez bien le wiki : <a href=\"http://wiki.spipu.net/doku.php?id=html2pdf:Accueil\">http://wiki.spipu.net/doku.php?id=html2pdf:Accueil</a>.</li>\n    </ul>\n</page>";

?>
