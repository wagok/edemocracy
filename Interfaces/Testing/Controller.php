<?php
if (file_exists ( 'Interfaces/Testing/languages/' . Econgress::$Member->Language . '.php' )) {
	require_once ('Interfaces/Testing/languages/' . Econgress::$Member->Language . '.php');
} else {
	require_once ('Interfaces/Testing/languages/english.php');
			}
//require_once 'languages/english.php';

require_once 'systemMesages.php';

if ($GURL->form != '') {
	
	// Сначала проверим не форма ли это
	$form = $GURL->form;
	$GURL->form = '';
	$PAGE_LINK = SITE_PATH . $GURL;
	
	// Формы (не действия)
	// 1 - подписать - отписать инициативу
	// 2 - выбрать другого члена
	// 3 - отдать голос
	// Таблица инициатив
	switch ($form) {
		case 1 :
			// Вывод формы подписи или отмены подписи по инициативе
			include 'form1_SignUnsign.php';
			break;
		case 2 :
			include 'form2_ChangeMember.php';
			break;
		case 3 :
			// Вывод формы голосования по инициативе
			include 'form3_Voting.php';
			break;
		case 4 :
			// Изменение дополнительной информации по инициативе автором
			include 'form4_InitiativeInform.php';
			break;
		case 5 : 
			include 'form5_NewInitiative.php';
			break;
		case 6:
			// Подтверждение удаления делегирования
			include 'form6_DelegationRemove.php';
			break;
		case 7:
			// Форма изменения делегирования
			include 'form7_ChangeDelegation.php';
			break;
		case 8:
			// Форма добавления делегирования
			include 'form8_NewDelegation.php';
			break;
		case 9:
			// Форма для классификации инициативы (выбор элемента классификации)
			include 'form9_Classify.php';
			break;
		case 10:
			// Форма для переклассификации инициатив
			include 'form10_ReClassify.php';
			break;
		case 11:
			// Добавление нового элемента классификации как потомка
			include 'form11_AddChildClassification.php';
			break;
		case 12:
			// Удаление элемента классификации
			include 'form12_RemoveClassificationElement.php';
			break;	
		case 13:
			// Изменение информации по классификации
			include 'form13_ChangeClassificationInfo.php';
			break;
		case 14:
			// Делегирование права классифицировать
			include 'form14_DelegationToClassify.php';
			break;
		case 15:
			// Добавление новой классификации
			include 'form15_NewClassification.php';
			break;
		case 16:
			// Отмена права классифицировать
			include 'form16_RemoveDelegationToClassify.php';
			break;
		case 17:
			// Форма изменения данный пользователя
			include 'form17_ChangeUserName.php';
			break;
		case 18:
			// Форма перепрописки пользователя
			include 'form18_UserRelocation.php';
			break;	
		case 19:
			// Форма изменения пароля пользователя
			include 'form19_ChangePass.php';
			break;
		case 20:
			// Форма детальной информации о пользователе
			include 'form20_MemberInfo.php';
			break;	
		case 21:
			// Форма ввода новой ветки локации
			include 'form21_AddChildLocation.php';
			break;
		case 22:
			// Форма удаления ветки локации
			include 'form22_RemoveLocationElement.php';
			break;
		case 23:
			// Форма редактирования ветки локации
			include 'form23_ChangeLocationElement.php';
			break;
		case 24:
			// Форма административной перепрописки пользователя
			include 'form24_AdminUserRelocation.php';
			break;
		case 25:
			// Форма административной групповой перепрописки из одной локации в другую
			include 'form25_GroupRelocation.php';
			break;
		case 26:
			// Форма ввода данных нового пользователя
			include 'form26_NewUser.php';
			break;
		case 88 :
			include 'form88_quickNotes.php';
			break;
		default :
		case 0 :
			// неверная форма - переадресация на страницу
			header ( 'Location: ' . SITE_PATH . $GURL );
			exit ();
	}
} else {
	include 'page0_menu.php';
	switch ($GURL->page) {
		case 0 :
		
		case 1 :
			include 'page1_Main.php';
			break;
		case 2 :
			include 'page2_SignInitiatives.php';
			break;
		case 3 :
			include 'page3_voting.php';
			break;
		case 4 :
			include 'page4_myInitiatives.php';
			break;
		case 5 :
			include 'page5_delegations.php';
			break;
		case 6 :
			include 'page6_ClassifyPage.php';
			break;
		case 7 :
			include 'page7_Classifications.php';
			break;
		case 8 :
			include 'page8_UserProfile.php';
			break;
		case 9 :
			include 'page9_Members.php';
			break;
		case 10 :
			include 'page10_LocationsTree.php';
			break;	
		case 11:
			include 'page11_AdminPage.php';
			break;			
		default :
			include 'page1_Main.php';
			break;
	}
}

?>