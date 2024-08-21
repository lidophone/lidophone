
// https://github.com/ably/laravel-broadcaster#using-laravel-echo-on-client-side

import Echo from '@ably/laravel-echo'
import * as Ably from 'ably'

const API_URL = '/api/v1'

window.Ably = Ably
window.Echo = new Echo({
  broadcaster: 'ably',
})

window.addEventListener('DOMContentLoaded', _ => {

  window.Echo.connector.ably.connection.on(stateChange => {
    if (stateChange.current === 'connected') {
      axios.get(API_URL + '/event-trigger', {
        params: {
          event: 'DailyCompetitionUpdate',
        }
      })
    }
  })

  const dailyCompetitionTableBody = document.getElementById('daily-competition-table-body-today')

  window.Echo.private('daily-competition')
    .listen('DailyCompetitionUpdate', response => {

      const table = [
        {transfersNeeded: 40, award: '6 000₽', color: '#c280ff', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 35, award: '5 000₽', color: '#8080ff', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 30, award: '4 000₽', color: '#81d3f8', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 25, award: '3 000₽', color: '#80ffff', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 20, award: '2 000₽', color: '#caf982', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 15, award: '1 000₽', color: '#ffff80', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 10, award: '600₽', color: '#facd91', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 5, award: '250₽', color: '#ec808d', manager: [{fullName: '—', madeTransfers: 0}]},
        {transfersNeeded: 1, award: '—', color: '#ffffff', manager: [{fullName: '—', madeTransfers: 0}]},
      ]

      const data = response.data

      table.forEach((row, rowIndex) => {
        data.forEach(managerData => {
          if (
            (rowIndex === 0 || rowIndex === table.length) &&
            managerData.madeTransfers >= row.transfersNeeded
          ) {
            table[rowIndex].manager.push({
              fullName: managerData.manager,
              madeTransfers: managerData.madeTransfers,
            })
          } else if (table[rowIndex + 1]) {
            if (
              managerData.madeTransfers >= table[rowIndex + 1].transfersNeeded &&
              managerData.madeTransfers < row.transfersNeeded
            ) {
              table[rowIndex + 1].manager.push({
                fullName: managerData.manager,
                madeTransfers: managerData.madeTransfers,
              })
            }
          }
        })
      })

      let tableBody = ''

      table.forEach(row => {

        let managers = ''
        row.manager.forEach((manager, index) => {
          if (row.manager.length > 1 && index === 0) {
            return
          }
          managers += manager.fullName +
            (manager.madeTransfers ? ' (' + manager.madeTransfers + ')<br>' : '')
        })

        tableBody +=
          `<tr>
            <td style="background:${row.color}">${row.transfersNeeded === 1 ? '1-4' : row.transfersNeeded}</td>
            <td style="background:${row.color}">${row.award}</td>
            <td style="background:${row.color}">` + managers + `</td>
          </tr>`
      })

      dailyCompetitionTableBody.innerHTML = tableBody
    })

})
