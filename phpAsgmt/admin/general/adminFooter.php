<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            *{
                font-family: arial;
            }
            .tableCss tr,.tableCss td,.tableCss th{
                border:none;
                width: 5%;
                text-align: center;
                padding-bottom:10px; 
                padding-top:10px; 
                transition: 0.2s linear;
            }
                        
            .tableCss td a:hover,tableCss th a:hover{
                border-bottom: black solid 3px;
            }
            
           .tableCss td a{
                font-size: 13px;
                color: grey;
                text-decoration: none;
            }
            
            footer{
                margin-top: 5%;
            }
            
            .tableCss td a.image,tableCss td a:image:hover{
                border:none;
                border-bottom: none;
            }
            
        </style>
    </head>
    <body>
        <footer>
            <hr>
            <div class="footer">
                
                
                <table class='tableCss'>
                    <tr>
                        <th colspan='1' rowspan=''3>Event4You</th>
                        <td></td>
                        <th>Topic</th>
                        <th>Topic</th>
                        <th>Topic</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a></td>
                        <td><a href=''>Page</a>
                    </tr>
                    <tr>
                        <td class='image'>
                        <a href='https://web.facebook.com' class='image'><img src='../general/image/facebookGrey.png' alt='facebook'  width='30px' height='30px'></a>&nbsp;&nbsp;
                        <a href='https://www.instagram.com/' class='image'><img src='../general/image/instagramGrey.png' alt='instagram' width='30px' height='30px' ></a>&nbsp;&nbsp;
                        <a href='https://www.youtube.com/' class='image'><img src='../general/image/youtubeGrey.png' alt='youtube' width='32px' height='30px' ></a>&nbsp;&nbsp;
                        
                        </td>
                        <td></td>
                        <td><a href='https://web.facebook.com'>Facebook</a></td>
                        <td><a href='https://www.instagram.com/'>Instagram</a></td>
                        <td><a href='https://www.youtube.com/'>Youtube</a></td>
                    </tr>
                </table>
            </div>


        </footer>
    </body>
</html>
