import { useState } from 'react'
import { invoke } from '@tauri-apps/api/core'
import './App.css'

function App() {
  const [message, setMessage] = useState('')

  async function greet() {
    setMessage(await invoke('greet', { name: 'MonLogiciel' }))
  }

  return (
    <div className="App">
      <h1>Welcome to MonLogiciel</h1>
      <div className="card">
        <button onClick={greet}>
          Greet
        </button>
        <p>{message}</p>
      </div>
      <p className="read-the-docs">
        Click on the Tauri, React logos to learn more
      </p>
    </div>
  )
}

export default App
