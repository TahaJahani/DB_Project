import React from 'react';
import { Grid, TextField, Button, RadioGroup, FormControlLabel, Radio } from '@mui/material'

const axios = require('axios');

export default function RawSql({ setData, setHeaders, setError }) {

    const [sql, setSql] = React.useState('');
    const [type, setType] = React.useState('select');

    const runSql = () => {
        axios.get('http://localhost:8000/api/sql', {
            params: {
                sql: sql,
                type: type,
            }
        }).then((res) => {
            setData(res.data.map((item, index) => ({ ...item, id: index })));
            setHeaders(Object.keys(res.data[0]).map(key => ({ field: key, headerName: key, flex: 1 })))
        }, (err) => {
            setError(err.response.data.message);
            console.log(err.response);
        })
    }

    return (
        <Grid container>
            <RadioGroup
                row
                value={type}
                onChange={(e) => setType(e.target.value)}
                name="row-radio-buttons-group">
                <FormControlLabel value="select" control={<Radio />} label="Select" />
                <FormControlLabel value="update" control={<Radio />} label="Update" />
                <FormControlLabel value="insert" control={<Radio />} label="Insert" />
            </RadioGroup>
            <Grid item xs={12}>
                <TextField
                    value={sql}
                    onChange={(e) => {setSql(e.target.value); setError('')}}
                    sx={{ width: '100%' }}
                    multiline
                    rows={4}
                    label="Query" />
            </Grid>
            <Grid item xs={12} justifyItems='flex-end' sx={{ my: 2 }}>
                <Button variant='contained' onClick={runSql}>
                    Execute
                </Button>
            </Grid>
        </Grid>
    )
}