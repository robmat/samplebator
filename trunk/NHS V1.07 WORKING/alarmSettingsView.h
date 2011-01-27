//
//  alarmSettingsView.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 24/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface alarmSettingsView : UIViewController {

	IBOutlet UIView * view1;
	IBOutlet UIView * view2 ;
	
	IBOutlet UITextField * nameField ;
	IBOutlet UITextField * dobField ;
	IBOutlet UITextField * nextkinField ;
	IBOutlet UITextField * bloodField ;
	IBOutlet UITextField * alergiesField ;
	
	IBOutlet UILabel * nameLabel ;
	IBOutlet UILabel * dobLabel ;
	IBOutlet UILabel * nextkinLabel ;
	IBOutlet UILabel * bloodLabel ;
	
	

	
}

-(IBAction)TestButton:(id)sender ;
-(IBAction)goBackButton:(id)sender ;
-(NSString *) dataFilePath ;

@end
