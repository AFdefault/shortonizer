async function formSubmit(form) {

    let resp = await fetch('/api/short_url', {
        body: new FormData(form),
        method: 'post',
    })

    let data;
    let resultSpan = document.getElementById('result')
    if (resp.ok) {
        data = await resp.json()
        resultSpan.value = data[0]['short_url']
        resultSpan.parentElement.style.display = 'block';
    }
}

async function copyLink(){
    const short_url = document.getElementById('result').value;
    console.log(short_url)
    try {
        await navigator.clipboard.writeText(short_url);
        alert('Ссылка успешно скопирована в буфер обмена')
    } catch (err) {
        alert('Ошибка копирования: ' + err);
    }
}