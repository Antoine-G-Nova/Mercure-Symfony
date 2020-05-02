const url = new URL('http://localhost:3000/.well-known/mercure')
url.searchParams.append('topic', 'http://demo/books/1')

const eventSource = new EventSourcePolyfill(url, {
  headers: {
    Authorization: 'Bearer ' + token,
  },
})

// The callback will be called every time an update is published
eventSource.onmessage = (e) => {
  let data = JSON.parse(e.data)
  let title = data.type

  var message = {
    title: "<strong>"+  title.charAt(0).toUpperCase() + title.slice(1) +"</strong> <br />",
    message: data.message
  }
  var paramNotify = {
    type: 'success',
    showProgressbar: false,
    placement: {
      from: 'bottom',
      align: 'right',
    },
    offset: 20,
    spacing: 20,
  }
  if (data.type !== 'ping') {
    paramNotify.type = 'warning'
  }
  $.notify(message, paramNotify)

}
