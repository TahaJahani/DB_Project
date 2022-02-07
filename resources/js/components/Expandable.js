import * as React from 'react';
import { Card, CardHeader, Collapse, Typography, IconButton } from '@mui/material'
import { styled } from '@mui/material/styles';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore'
const ExpandMore = styled((props) => {
    const { expand, ...other } = props;
    return <IconButton {...other} />;
})(({ theme, expand }) => ({
    transform: !expand ? 'rotate(0deg)' : 'rotate(180deg)',
    transition: theme.transitions.create('transform', {
        duration: theme.transitions.duration.shortest,
    }),
}));

export default function Expandable(props) {

    return (
        <Card sx={{my: 2, ...props.sx}}>
            <CardHeader
                onClick={props.onExpand}
                title={<Typography variant='h6'>
                    {props.title}
                </Typography>}
                action={<ExpandMore expand={props.expanded} onClick={props.onExpand}>
                    <ExpandMoreIcon />
                </ExpandMore>}
            />
            <Collapse unmountOnExit in={props.expanded} sx={{ padding: 2 }}>
                {props.children}
            </Collapse>
        </Card>
    )
}