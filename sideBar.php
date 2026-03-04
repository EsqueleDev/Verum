<div class='SideBar'>
        <span>
            <a href='newPost'><div class='SideBarContent hideMobile'>
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M186.67-186.67H235L680-631l-48.33-48.33-445 444.33v48.33ZM120-120v-142l559.33-558.33q9.34-9 21.5-14 12.17-5 25.5-5 12.67 0 25 5 12.34 5 22 14.33L821-772q10 9.67 14.5 22t4.5 24.67q0 12.66-4.83 25.16-4.84 12.5-14.17 21.84L262-120H120Zm652.67-606-46-46 46 46Zm-117 71-24-24.33L680-631l-24.33-24Z"/></svg>
                <h3>Fazer um Post</h3>
            </div></a>
            <div class='SideBarContent'>
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M120-513.33V-840h326.67v326.67H120ZM186.67-580H380v-193.33H186.67V-580ZM120-120v-326.67h326.67V-120H120Zm66.67-66.67H380V-380H186.67v193.33Zm326.66-326.66V-840H840v326.67H513.33ZM580-580h193.33v-193.33H580V-580Zm178.67 460v-81.33H840V-120h-81.33ZM513.33-364.67v-82h81.34v82h-81.34Zm81.34 81.34v-81.34h82v81.34h-82Zm-81.34 82v-82h81.34v82h-81.34ZM594.67-120v-81.33h82V-120h-82Zm82-81.33v-82h82v82h-82Zm0-163.34v-82h82v82h-82Zm82 81.34v-81.34H840v81.34h-81.33Z"/></svg>
                <h3>QR-Code de amigo</h3>
            </div>
            <div class='SideBarContent hidePC'>
                <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#e3e3e3"><path d="M479.67-264.67q73.33 0 123.5-50.16 50.16-50.17 50.16-123.5 0-73.34-50.16-123.17-50.17-49.83-123.5-49.83-73.34 0-123.17 49.83t-49.83 123.17q0 73.33 49.83 123.5 49.83 50.16 123.17 50.16Zm0-66.66q-45.67 0-76-30.67-30.34-30.67-30.34-76.33 0-45.67 30.34-76 30.33-30.34 76-30.34 45.66 0 76.33 30.34 30.67 30.33 30.67 76 0 45.66-30.67 76.33t-76.33 30.67ZM146.67-120q-27 0-46.84-19.83Q80-159.67 80-186.67v-502q0-26.33 19.83-46.5 19.84-20.16 46.84-20.16h140L360-840h240l73.33 84.67h140q26.34 0 46.5 20.16Q880-715 880-688.67v502q0 27-20.17 46.84Q839.67-120 813.33-120H146.67Zm0-66.67h666.66v-502H642.67l-73-84.66H390.33l-73 84.66H146.67v502ZM480-438Z"/></svg>
                <h3>Ler QR Code</h3>
            </div>
            <br>
            <hr>
            <br>
            <h2>Seus Amigos:</h2>
            <?php getAllFriendFromUser($conn, $me['id']); ?>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>Usuario</h3>
            </div>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>Usuario</h3>
            </div>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>Usuario</h3>
            </div>
            <br>
            <hr>
            <br>
            <h2>Seus Jogos e Add-Ons:</h2>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>Colheita Feliz</h3>
            </div>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>Editor de Perfil</h3>
            </div>
            <div class='SideBarContent'>
                <img src='Default_Profile_Pics/10.png' style="max-width: 48px; border-radius: 50%;">
                <h3>PokeCats</h3>
            </div>
        </span>
    </div>