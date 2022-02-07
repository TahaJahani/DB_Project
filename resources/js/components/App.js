import React from 'react';
import ReactDOM from 'react-dom';
import MainPanel from './MainPanel';

const axios = require('axios');

export default function App() {

    return (
        <div>
            <MainPanel />
        </div>
    );
}

ReactDOM.render(<App />, document.getElementById("app"));
