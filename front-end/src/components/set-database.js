import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Spinner from 'react-bootstrap/Spinner';
import Card from 'react-bootstrap/Card';
import { useFetch } from '../helpers/remotehelper';
import { useHistory } from 'react-router-dom';

export default function SetUpDataBase() {
    const history = useHistory();
    const staffList = () => history.push('staff-list');
    const [isLoading, setIsLoading] = useState(true);

    const response = useFetch("http://localhost:8000/", {
        mode: "cors", method: 'POST'
    },[isLoading]);

    if (response != null) {
        if (response.response) {
            console.log(isLoading);
            console.log(response);
            if(isLoading){
                setIsLoading(false);
            }else{
                staffList();
            }
        }
    }

    if (response.error) {
        return <div>Error: Unable to setup database</div>;
    } else {
        return (
            <Card className="text-center">
                <Card.Header>Setting Up DataBase</Card.Header>
                <Card.Body>
                    <Card.Title>Getting a few things ready</Card.Title>
                    <Card.Text>
                        {isLoading ? <Spinner
                            as="span"
                            animation="grow"
                            variant="primary"
                            role="status"
                        /> : ''}
                    </Card.Text>
                    <Button variant="primary" disabled={isLoading} onClick={setIsLoading}>
                        {isLoading ? 'Setting up database....' : 'Setup Database'}
                    </Button>
                </Card.Body>
                <Card.Footer className="text-muted">TopSoft Inc. 2020</Card.Footer>
            </Card>
        )
    }
}