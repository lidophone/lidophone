
/* global I18N */

window.addEventListener('DOMContentLoaded', _ => {
  // https://stackoverflow.com/a/9251864/4223982
  const oldLogo = document.querySelector('header [href="/nova/dashboards/main"]')
  const newLogo = oldLogo.cloneNode(true)
  oldLogo.parentNode.replaceChild(newLogo, oldLogo)

  const events = ['click', 'auxclick']
  events.forEach(item => {
    newLogo.addEventListener(item, e => {
      e.preventDefault()
      open(Nova.config('brandUrl'))
    })
  })
})
