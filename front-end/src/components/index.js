import React from 'react';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import { useHistory } from 'react-router-dom';

export default function Index() {
    const history = useHistory();
    const dbSetUp = () => history.push('db-setup');
    return (
        <Card className="text-center">
            <Card.Header>WELCOME</Card.Header>
            <Card.Body>
                <Card.Title>Ready to roll?</Card.Title>
                <Button variant="primary" onClick={dbSetUp}>
                    Setup Database
                </Button>
            </Card.Body>
            <Card.Footer className="text-muted">TopSoft Inc. 2020</Card.Footer>
        </Card>
    )

}