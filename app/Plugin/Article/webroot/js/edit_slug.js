function article_onChangeTitle(eTitle, eSlug) {
	if (!slug_EditMode) {
		$(eSlug).val(translit($(eTitle).val()));
	}
}

function article_onChangeSlug(eSlug) {
	slug_EditMode = ($(eSlug).val() && true);
}

function translit(str) {
	return ru2en.tr_url(str);
}
