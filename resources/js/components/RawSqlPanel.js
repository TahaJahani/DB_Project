import React from 'react';
import DataTable from './DataTable'
import RawSql from './RawSql';

export default function RawSqlPanel() {

    const [data, setData] = React.useState([]);
    const [headers, setHeaders] = React.useState([]);

    return (
        <div>
            <RawSql setData={setData} setHeaders={setHeaders} />
            <DataTable data={data} headers={headers} />
        </div>
    );
}
