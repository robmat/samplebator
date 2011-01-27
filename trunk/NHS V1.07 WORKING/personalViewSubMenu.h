//
//  personalViewSubMenu.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 23/02/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface personalViewSubMenu : UIViewController {

	IBOutlet UIImageView * viewPersonalBar ;
	BOOL avanzar ;
}

-(IBAction)AlarmSettingsButton:(id)sender ;
-(IBAction)MyAppointmentsButton:(id)sender ;
-(IBAction)MyNotesButton:(id)sender ;

@end
