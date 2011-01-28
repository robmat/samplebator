//
//  alarmSettingsNEW.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 09/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface alarmSettingsNEW : UITableViewController <UITableViewDelegate> {

	NSMutableArray * lista ;

	NSMutableArray * states ;
	NSMutableArray * capitals ;
	
	IBOutlet UILabel * textLabel ;	
	
	IBOutlet UIView * myView ;
	
	//UILabel * tempoLabel ;
	
	IBOutlet UITableView * myTableView ;
	BOOL avanzar ;
	//In states im going to have the customer information
}

-(IBAction)loadButton:(id)sender ;



@property (nonatomic,retain) 	NSMutableArray * lista ;

@end
