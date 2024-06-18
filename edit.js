// file for block

import { __ } from '@wordpress/i18n';

//import { useEffect } from 'react';

import { Placeholder, TextControl,Toolbar,ToolbarGroup,ToolbarButton } from '@wordpress/components';
import {
	Card,
	CardHeader,
	CardBody,
	CardFooter
  } from '@wordpress/components';

import { useBlockProps,useInnerBlocksProps,BlockControls } from '@wordpress/block-editor';

import { useState } from "react";

import { ToolbarDropdownMenu } from '@wordpress/components';

const componentStyle ={
	height:40,
	marginTop:10,
	width:330,
	marginLeft:10
};

const defaultImageName = require('./my-img.png')

function selectImage(url)
{
	alert(url);
	
	// Add custom code here for javascript to select image, upload to wordpress, and insert into post.

}

function ShowImages(props)
{

if(props.mediaLists.length>0)
	{
		console.log(props.mediaLists);
		
		// create card component here.

		return (<div style={{display:"table",clear:"both",paddingTop:10}}>
								{props.mediaLists.map(image => (
									<div key={image.id} style={{float:"left",width:"auto%",padding:"0 10px", marginTop:"10px"}}>
										<Card>
											<CardBody>
												<img src={image.media} onClick={()=>selectImage(image.media)} />
											</CardBody>
										</Card>
									</div>
								))}
		</div>);
		
	}
	else
	{
    	return (<img src={defaultImageName} alt="placeholder" />);
	}
}


export default function Edit()
{
let nextId = 0;
let [mediaLists, setMediaLists] = useState([]);
const blockProps = useBlockProps();
const innerBlocksProps = useInnerBlocksProps();


const generateImages = () => {
	
	// call promise to fetch images.

	let nextId=mediaLists.length+1;
	setMediaLists([
		...mediaLists,
		{ id: nextId, media: "https://via.placeholder.com/150" }
	  ]);
}

	return (
		<div {...blockProps}>
			<div  {...innerBlocksProps}>
				<BlockControls>
				<ToolbarGroup>
					<ToolbarDropdownMenu
							label="Select a direction"
							controls={ [
								{
									title: 'Stock Image',
									onClick: () => console.log( 'up' ),
								},
								{
									title: 'Illustration',
									onClick: () => console.log( 'right' ),
								},
								{
									title: 'Sketch',
									onClick: () => console.log( 'down' ),
								},
								{
									title: 'Real',
									onClick: () => console.log( 'left' ),
								},
							] }
						/>
						
						<Toolbar label="Options">
								<TextControl style={componentStyle}  placeholder="Enter Prompt" />
								<ToolbarButton label="Bold" onClick={()=>generateImages()}>
									Generate Image
								</ToolbarButton>
						</Toolbar>
						</ToolbarGroup>
				</BlockControls>
				</div>

			<div { ...useBlockProps() }>
				<Placeholder label="GenAI Image">
						<ShowImages mediaLists = {mediaLists} />
				</Placeholder>
			</div>
		</div>
	);
}
