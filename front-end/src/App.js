import asyncComponent from './components/GenericComponents/AsyncConpoment';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import React from 'react';
import './App.css';
const Index = asyncComponent(() => import('./components/index').then(module => module.default));
const SetUpDataBase = asyncComponent(() => import('./components/set-database').then(module => module.default));
const StaffList = asyncComponent(() => import('./components/staff-list').then(module => module.default));
function App() {
  return (
    <Router>
      <div>
        <Switch>
          <Route exact path='/' component={Index} />
          <Route exact path='/db-setup' component={SetUpDataBase} />
          <Route exact path='/staff-list' component={StaffList} />
        </Switch>
      </div>
    </Router>
  );
}

export default App;
