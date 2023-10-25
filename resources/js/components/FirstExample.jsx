import React from 'react';
import ReactDOM from 'react-dom/client';

export default function Example(){
    return(
        <h1>Welcome to ReactJS</h1>
    );
}

const container = document.getElementById('Example');
const root = ReactDOM.createRoot(container);
root.render(<Example/>);