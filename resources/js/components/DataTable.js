import React from 'react';
import { Divider } from '@mui/material'
import { DataGrid } from '@mui/x-data-grid';

export default function DataTable({ data, headers }) {
    return (
        <div>
            <Divider variant='fullWidth' sx={{ my: 2 }} />
            <DataGrid rows={data} columns={headers} autoHeight />
        </div>
    )
}