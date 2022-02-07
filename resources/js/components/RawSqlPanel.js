import { Typography } from '@mui/material';
import React from 'react';
import DataTable from './DataTable'
import RawSql from './RawSql';

export default function RawSqlPanel() {

    const [data, setData] = React.useState([]);
    const [headers, setHeaders] = React.useState([]);
    const [error, setError] = React.useState('');

    return (
        <div>
            <RawSql setData={setData} setHeaders={setHeaders} setError={setError} />
            <Typography color='error'>
                {error}
            </Typography>
            <DataTable data={data} headers={headers} />
        </div>
    );
}
