import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Spinner from 'react-bootstrap/Spinner';
import Card from 'react-bootstrap/Card';
import { useFetch } from '../helpers/remotehelper';
import Table from 'react-bootstrap/Table';
export default function StaffList() {

    const [isLoading, setIsLoading] = useState(true);

    const response = useFetch("http://localhost:8000/get-staff", {
        mode: "cors", method: 'GET'
    }, isLoading);

    console.log(response);
    if (response.error) {
        return <div>Error: Unable to initialize database</div>;
    } else {
        return (
            <Card className="text-center">
                <Card.Header></Card.Header>
                <Card.Body>
                    <Card.Title>Employees</Card.Title>
                    <Card.Text>
                        <Table striped bordered hover>
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Designation</th>
                                    <th>Role</th>
                                    <th>Time Spent At Company</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            {response.response && <tbody>
                                {
                                    response.response.employeelist.map((val, index) => (
                                        <tr key={index}>
                                            <td>{val.employee_id}</td>
                                            <td>{val.name}</td>
                                            <td>{val.employee_id}</td>
                                            <td>{val.description}</td>
                                            <td>{val.userRole}</td>
                                            <td>{val.employee_id}</td>
                                            <td>{val.employee_id}</td>
                                        </tr>
                                    ))}
                            </tbody>
                            }

                        </Table>
                    </Card.Text>
                    <Button variant="primary" disabled={isLoading} onClick={setIsLoading}>
                        Refresh List
                    </Button>
                </Card.Body>
                <Card.Footer className="text-muted">TopSoft Inc. 2020</Card.Footer>
            </Card>
        )
    }
}