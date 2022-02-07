import { Button, Grid, MenuItem, TextField, Typography } from '@mui/material';
import React from 'react';
import Expandabl from './Expandable'
import DataTable from './DataTable'

const axios = require('axios');

export default function BuildPanel() {

    const [data, setData] = React.useState([]);
    const [headers, setHeaders] = React.useState([])
    const [executedQuery, setExecutedQuery] = React.useState('')
    const [step, setStep] = React.useState(0)
    const [modelData, setModelData] = React.useState();
    const [writtenMethod, setWrittenMethod] = React.useState('')
    const [writtenParams, setWrittenParams] = React.useState('')
    const [req, setReq] = React.useState({
        query_name: "",
        query_params: '["*"]',
        model: "",
        methods: {},
    });

    const addMethosToReq = () => {
        let inputs = writtenParams.split(",")
        setReq({ ...req, methods: { ...req.methods, [writtenMethod]: inputs } })
        setWrittenMethod('')
        setWrittenParams('')
    }

    const buildrequest = () => {
        axios.post("http://localhost:8000/api/build", {...req, query_params: JSON.parse(req.query_params)})
            .then((res) => {
                let toShow = `Executed Query: \n ${res.data.query[0].query}\n\n With Params: \n ${JSON.stringify(res.data.query[0].bindings)}`;
                setExecutedQuery(toShow)
                setData(res.data.data.map((item, index) => ({ ...item, id: index })));
                setHeaders(Object.keys(res.data.data[0]).map(key => ({ field: key, headerName: key, flex: 1 })))
            }, (err) => { console.log(err.response) })
        setReq({
            query_name: "",
            query_params: ["*"],
            model: "",
            methods: {},
        })
        setStep(0)
    }

    React.useEffect(() => {
        axios.get("http://localhost:8000/api/model-data")
            .then((res) => {
                setModelData(res.data);
            }, (err) => { console.log(err.response) })
    }, [])

    return (
        <div>
            <Typography sx={{ my: 2 }} variant='h6'>
                {executedQuery}
            </Typography>

            <Expandabl title="Query" expanded={step === 0} onExpand={() => { }}>
                <Grid container>
                    <Grid item xs={6}>
                        <TextField
                            sx={{ width: "100%" }}
                            value={req.query_name}
                            onChange={(e) => setReq({ ...req, query_name: e.target.value })}
                            label="Query"
                            select>
                            <MenuItem key="select" value="select">select</MenuItem>
                            <MenuItem key="update" value="update">update</MenuItem>
                            <MenuItem key="insert" value="insert">insert</MenuItem>
                            <MenuItem key="delete" value="delete">delete</MenuItem>
                        </TextField>
                    </Grid>
                    <Grid item xs={1}>
                        <Button variant='outlined' onClick={() => setStep(1)}>Submit</Button>
                    </Grid>
                </Grid>
            </Expandabl>


            <Expandabl title="Model Name" expanded={step === 1} onExpand={() => { }}>
                <Grid container>
                    <Grid item xs={6}>
                        <TextField
                            sx={{ width: "100%" }}
                            value={req.model}
                            onChange={(e) => setReq({ ...req, model: e.target.value })}
                            label="Model"
                            select>
                            {modelData && Object.keys(modelData).map((item) =>
                                <MenuItem key={item} value={item}>{item}</MenuItem>
                            )}
                        </TextField>
                    </Grid>
                    <Grid item xs={1}>
                        <Button variant='outlined' onClick={() => setStep(2)}>Submit</Button>
                    </Grid>
                </Grid>
            </Expandabl>

            <Expandabl title="Query Params" expanded={step === 2} onExpand={() => { }}>
                <Grid container>
                    <Grid item xs={6}>
                        <TextField
                            sx={{ width: "100%" }}
                            value={req.query_params}
                            onChange={(e) => setReq({ ...req, query_params: e.target.value })}
                            label="Query Params" />
                    </Grid>
                    <Grid item xs={1}>
                        <Button variant='outlined' onClick={() => setStep(3)}>Submit</Button>
                    </Grid>
                </Grid>
            </Expandabl>

            <Expandabl title="Methods" expanded={step === 3} onExpand={() => { }}>
                <Grid container>
                    <Grid item xs={4}>
                        <TextField
                            sx={{ width: "100%" }}
                            value={writtenMethod}
                            onChange={(e) => setWrittenMethod(e.target.value)}
                            label="Query Params"
                            select>
                            <MenuItem key="distinct" value="distinct">distinct</MenuItem>
                            <MenuItem key="join" value="join">inner join</MenuItem>
                            <MenuItem key="leftJoin" value="leftJoin">left join</MenuItem>
                            <MenuItem key="rightJoin" value="rightJoin">right join</MenuItem>
                            <MenuItem key="where" value="where">where</MenuItem>
                            <MenuItem key="orWhere" value="orWhere">or where</MenuItem>
                            <MenuItem key="orderBy" value="orderBy">order by</MenuItem>
                            <MenuItem key="groupBy" value="groupBy">group by</MenuItem>
                            <MenuItem key="having" value="having">having</MenuItem>
                        </TextField>
                    </Grid>
                    <Grid item xs={4}>
                        <TextField
                            sx={{ width: "100%" }}
                            value={writtenParams}
                            onChange={(e) => setWrittenParams(e.target.value)}
                            label="Inputs" />
                    </Grid>
                    <Grid item xs={1}>
                        <Button variant='outlined' onClick={addMethosToReq}>Add Method</Button>
                    </Grid>
                </Grid>
                <Grid container>
                    <Typography>
                        {JSON.stringify(req.methods)}
                    </Typography>
                </Grid>
                <Grid container sx={{my:2}}>
                    <Button variant='contained' onClick={buildrequest}>Execute</Button>
                </Grid>
            </Expandabl>

            <DataTable data={data} headers={headers} />
        </div>
    );
}
