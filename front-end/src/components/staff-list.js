import React, { useState, useEffect } from 'react';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import { useFetch } from '../helpers/remotehelper';
import Table from 'react-bootstrap/Table';
import Modal from 'react-bootstrap/Modal';

export default function StaffList() {
    const [similarEmployees, setResponse] = useState({});
    const [isLoading, setIsLoading] = useState(true);
    const [show, displayModal] = useState(false);
    const [staffDetails, setDetails] = useState({});
    const handleClose = () => displayModal(false);
    const [error, setError] = useState(null);

    const handleShow = (val) => {
        displayModal(true);
        setDetails(val);
    }

    const response = useFetch("http://localhost:8000/get-staff", {
        mode: "cors",
        method: 'GET'
    }, [isLoading]);

    let url = 'http://localhost:8000/get-similar-staff?name=' + staffDetails.name;

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await fetch(url, {
                    mode: "cors",
                    method: 'GET'
                });
                const json = await res.json();
                setResponse(json);
            } catch (error) {
                setError(error);
            }
        };
        fetchData();
    }, [staffDetails.name]);

    if (response.error) {
        return <div>Error: Unable to initialize database</div>;
    } else {
        return (
            <Card className="text-center">

                <Modal
                    show={show}
                    onHide={handleClose}
                    backdrop="static"
                    keyboard={false}
                >
                    <Modal.Header closeButton>
                        <Modal.Title>Staff Details</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>

                        <Table striped bordered hover size="sm">
                            <tbody>
                                <tr>
                                    <td>Employee Id</td>
                                    <td>{staffDetails.employee_id}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>{staffDetails.name}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>{staffDetails.staff_status}</td>
                                </tr>
                                <tr>
                                    <td>Designation</td>
                                    <td>{staffDetails.designation}</td>
                                </tr>
                                <tr>
                                    <td>Role</td>
                                    <td>{staffDetails.user_role}</td>
                                </tr>
                                <tr>
                                    <td>Time Spent Status</td>
                                    <td>{staffDetails.time_spent_status}</td>
                                </tr>

                                <tr>
                                    <td>Start Date</td>
                                    <td>{staffDetails.start_date ? staffDetails.start_date : '(Not Set)'}</td>
                                </tr>

                                <tr>
                                    <td>Actions</td>
                                    <td>
                                        {staffDetails.actions && staffDetails.actions.length > 0 ? <ol>
                                            {
                                                staffDetails.actions.map((val, index) => (
                                                    <li key={index}>{val}</li>
                                                ))}
                                        </ol> : '(Not Set)'
                                        }
                                    </td>
                                </tr>

                                <tr>
                                    <td>Similar Staff</td>
                                    <td>
                                        {similarEmployees.employeelist && similarEmployees.employeelist.length > 0 ? <ol>
                                            {
                                                similarEmployees.employeelist.map((val, index) => (
                                                    <li key={index}>{val.name}</li>
                                                ))}
                                        </ol> : '(Not Set)'
                                        }
                                    </td>
                                </tr>
                            </tbody>
                        </Table>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={handleClose}>
                            Close
          </Button>
                    </Modal.Footer>
                </Modal>


                <Card.Header></Card.Header>
                <Card.Body>
                    <Card.Title>Employees</Card.Title>
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
                                        <td>{val.staff_status}</td>
                                        <td>{val.designation}</td>
                                        <td>{val.user_role}</td>
                                        <td>{val.time_spent_status}</td>
                                        <td>
                                            <div>
                                                <Button variant="primary" size="sm" onClick={() => handleShow(val)}>View Details</Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                        </tbody>
                        }
                    </Table>
                </Card.Body>
                <Card.Footer className="text-muted">TopSoft Inc. 2020</Card.Footer>
            </Card>
        )
    }
}