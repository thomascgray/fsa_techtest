import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router, Route} from 'react-router-dom'

import './index.css';

import App from './Screens/App/App';
import Establishments from './Screens/Establishments/Establishments';

ReactDOM.render(
    <Router>
        <div>
            <Route exact path="/" component={App}/>
            <Route exact path="/establishments" component={Establishments}/>
        </div>
    </Router>
	, document.getElementById('root'));
