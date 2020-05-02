const url = new URL('http://localhost:3000/.well-known/mercure')
url.searchParams.append('topic', 'http://demo/books/1')

const eventSource = new EventSourcePolyfill(url, {
  headers: {
    Authorization: 'Bearer ' + token,
  },
})

// The callback will be called every time an update is published
eventSource.onmessage = (e) => {
  console.log(e)
  document
    .querySelector('h1')
    .insertAdjacentHTML(
      'afterend',
      '<div class="alert alert-success " role="alert">YEEEEES</div>'
    )

  window.setTimeout(() => {
    const $alert = document.querySelector('.alert')
    $alert.parentNode.removeChild($alert)
  }, 2000)
}
