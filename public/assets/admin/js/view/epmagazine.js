function articleListRender(data, type, full, meta, column, params = null) {
    const articleList = full.article_list.filter((item) => {
        return item.article_cd === params.article_cd;
    });

    if(articleList.length) {
        let output = articleList.reduce((acc, curr) => {
            let inner = '';
            if(curr.hasOwnProperty('uploads') && curr.uploads.length > 0){
                inner = curr.uploads.reduce((html, item) => {
                    const wrap = document.createElement('span');
                    wrap.classList.add('m-1', 'd-inline-block');

                    const temp = `<img class="img-thumbnail d-inline rounded-2 overflow-hidden" src="${item.file_link}">`;
                    wrap.setAttribute('data-bs-content' , temp);
                    wrap.setAttribute('data-bs-toggle' , 'popover');
                    wrap.setAttribute('data-bs-trigger' , 'hover');
                    wrap.setAttribute('data-bs-placement' , 'right');
                    wrap.setAttribute('data-bs-html' , 'true');
                    wrap.setAttribute('data-bs-template' , '<div class="popover" role="tooltip"><div class="popover-arrow"></div><div class="popover-body p-0 rounded-3 overflow-hidden"></div></div>');

                    wrap.innerHTML = temp;

                    html += wrap.outerHTML;
                    return html;
                }, '')
            }else{
                inner = `<i class="${column.icon}"></i>`;
            }
            acc += inner;
            return acc;
        }, '');
        return `<div>${output}</div>`;
    }else{
        return '-';
    }
}
