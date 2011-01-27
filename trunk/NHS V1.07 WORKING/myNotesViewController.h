//
//  myNotesViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 11/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface myNotesViewController : UIViewController{

	NSMutableArray * lista ;
	
	NSMutableArray * states ;
	NSMutableArray * capitals ;
	
	IBOutlet UITableView * myTableView ;
	
	NSString * tempoMyNotes ;
	
	IBOutlet UITextView * mytextNote ;

	IBOutlet UIView * vistaNewView ;
	IBOutlet UILabel * mainText ;
	IBOutlet UITextView * insideText ;
	
}

-(NSString *) dataFilePath ;
-(IBAction) saveAction:(id)sender ;
-(IBAction) closeNewView ;
-(IBAction)	buttonMedication:(id)sender ;
-(IBAction)	buttonAllergies:(id)sender ;

@end
